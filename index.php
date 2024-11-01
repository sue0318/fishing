
<?php require 'header.php'; 
    $pdo = new PDO('mysql:host=localhost;dbname=fishing;charset=utf8','staff','password');
?>

<div class=form>
    <?php
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //ポスト送信がされたら処理する

            ?> <h2 id=error> <?php
            if((!isset($_REQUEST['place']) && $_REQUEST['miyazaki'] == '') or
            (!isset($_REQUEST['how']) && $_REQUEST['tool'] == '') or
            (!isset($_REQUEST['season'])) or ($_REQUEST['fish'] == '')){
                //場所・釣り方・時期・おさかながそれぞれ送られてきてるかチェック
                if(!isset($_REQUEST['place']) && $_REQUEST['miyazaki'] == ''){
                    //場所のセレクトボックスかテキストボックスに値があるか確認する
                    echo '場所を選択してください。';
                    ?> <br> <?php
                }

                if(!isset($_REQUEST['how']) && $_REQUEST['tool'] == ''){
                    //釣り方のセレクトボックスかテキストボックスに値があるか確認する
                    echo    '釣り方を選択してください。';
                    ?> <br> <?php
                }

                if(!isset($_REQUEST['season'])){
                    //時期のセレクトボックスに値があるか確認する
                    echo '時期を選択してください。';
                    ?> <br> <?php
                }

                if($_REQUEST['fish'] == ''){
                    //おさかなのテキストボックスに値があるか確認する
                    echo 'おさかなを入力してください。';
                    ?> <br> <?php
                }

                ?>
                <form action="insert.php">
                <input type="submit"  class="bottom" value="戻る">
                </form>
                <form action="index.php">
                <input type="submit"  class="bottom" value="検索へ">
                </form>
                <?php
                exit;
            }
            ?> </h2> <?php

            if(isset($_REQUEST['place'])){
                //$_REQUEST['place']に値が入っていれば処理する
                //!== ''じゃない理由は、$_REQUEST['place']がnullとして前ページから受け取るから？
                $count = substr(str_repeat(',?',count($_REQUEST['place'])),1);
                //$_REQUEST['place']をカウントしてsqlをその分実行する
                $sql = $pdo->prepare("SELECT place_id FROM place WHERE miyazaki IN ($count)");
                //""でsql分を実行して$countの分引っ張ってくる
                $sql->execute($_REQUEST['place']);
                //変数に代入してexecuteするとexecuteの真偽値だけが変数に代入されるため変数に代入しない
                $place = $sql->fetchAll(PDO::FETCH_ASSOC);
                //feachAllしたもののインデックスを省略し、変数に代入
                foreach($place as $row){
                    $place_id[] = $row['place_id'];
                }
                //連想配列の$placeを$place_id[]に突っ込む
                $spot = $place_id;
                //$place_idと$miyazakiを$spotにまとめる
            }

                if($_REQUEST['miyazaki'] !== ''){
                    //$_REQUEST['miyazaki']から値を受け取ったら処理
                    $sql = $pdo->prepare('INSERT INTO place VALUES(null,?)');
                    $miyazaki = $sql->execute([$_REQUEST['miyazaki']]);
                    //$_REQUEST['miyazaki']をDBに追加する
                    $miyazaki = $pdo->lastInsertId();
                    //さっき追加した$_REQUEST['miyazaki']のplace_idを取得する
                    $spot[] = $miyazaki;
                    //$place_idと$miyazakiを$spotにまとめる
                }

                if(isset($_REQUEST['how'])){
                    //placeの処理と同じ
                    $count = substr(str_repeat(',?',count($_REQUEST['how'])),1);
                    $sql = $pdo->prepare("SELECT how_id FROM how WHERE tool IN ($count)");
                    $sql->execute($_REQUEST['how']);
                    $how = $sql->fetchAll(PDO::FETCH_ASSOC);
                    foreach($how as $row){
                        $how_id[] = $row['how_id'];
                    }
                    $style = $how_id;
                }
        
                    if($_REQUEST['tool'] !== ''){
                        $sql = $pdo->prepare('INSERT INTO how VALUES(null,?)');
                        $tool = $sql->execute([$_REQUEST['tool']]);
                        $tool = $pdo->lastInsertId();
                        $style[] = $tool;
                    }

                    $sql = $pdo->prepare('SELECT fish_id FROM fish WHERE fish_name=?');
                    $sql->execute([$_REQUEST['fish']]);
                    $fish_id = $sql->fetch(PDO::FETCH_ASSOC);


                    if(!isset($fish_id)){
                        $sql = $pdo->prepare('INSERT INTO fish VALUES(null,?)');
                        [$fish_id] = $sql->execute([$_REQUEST['fish']]);
                        [$fish_id] = $pdo->lastInsertId();
                    }
                    print_r($fish_id);

            if(isset($spot,$style,$_REQUEST['season'],$fish_id)){
                foreach($spot as $place){
                    foreach($style as $how){
                        foreach($_REQUEST['season'] as $season){
                            //foreachで各項目をループさせながらテーブルresultに入れ込んでいく
                            $fish = $pdo->prepare('INSERT INTO result VALUES(null,?,?,?,?)');
                            $success = $fish->execute([$how,$place,$season,[$fish_id]]);
                        }
                        if(isset($success)){
                            echo '情報を入力しました。';
                        }
                    }
                }
            }
        }
    ?>
</div>

<h1>逆おさかな図鑑</h1>
<h2>宮崎で釣れるおさかなたちを 場所 と 釣り方 と 時期 で検索できるページです</h2>
<h3>通常の図鑑と違い、おさかなから時期や釣り方や場所を調べるのではなく、</h3>
<h3>今の時期で、行く先で、持ってる道具で、何が釣れるかを楽しんでもらえたらと思います。</h3>

<div class=form>
    <form action="result.php" method="post">

        <select name="place_id">
            <?php
                $sql = $pdo->query('SELECT * FROM place');
                foreach($sql as $place){
            ?>
                    <option value = <?= $place['place_id'] ?>><?= $place['miyazaki'] ?></option>
               <?php } ?>
        </select>

        <select name="how_id">
            <?php
               $sql = $pdo->query('SELECT * FROM how');
                foreach($sql as $how){
            ?>
                    <option value = <?= $how['how_id'] ?>><?= $how['tool'] ?></option>
                <?php } ?>
        </select>

        <select name="season_id">
            <?php
                $sql = $pdo->query('SELECT *  FROM season');
                foreach($sql as $season){
            ?>
                    <option value = <?= $season['season_id'] ?>><?= $season['month'] ?></option>
                <?php } ?>
        </select>

        <button>検索</button>


    </form>
</div>
<?php require 'footer.php'; ?>