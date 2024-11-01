<?php require 'header.php';
    $pdo = new PDO('mysql:host=localhost;dbname=fishing;charset=utf8','staff','password');
    
    $tool = $pdo->prepare('SELECT tool FROM how WHERE how_id=?');
    //テーブルhow内のカラムtoolで?に該当する行を探す
    $tool->execute([$_REQUEST['how_id']]);
    //さっきの?はこれでした
    $how = $tool->fetch(PDO::FETCH_ASSOC);
    //fecth(PDO::FETCH_ASSOC)でインデックスを省いてfetchする

    $month = $pdo->prepare('SELECT month FROM season WHERE season_id=?');
    $month->execute([$_REQUEST['season_id']]);
    $season = $month->fetch(PDO::FETCH_ASSOC);

    $miyazaki = $pdo->prepare('SELECT miyazaki FROM place WHERE place_id=?');
    $miyazaki->execute([$_REQUEST['place_id']]);
    $place = $miyazaki->fetch(PDO::FETCH_ASSOC);
?>

<div class=result>
    <h2><?= $place['miyazaki'].' で '.$how['tool'].' を 使って'.$season['month'].' 月 に'.$place['miyazaki'].' で 釣れる魚はこちら'; ?></h2>


    <?php    
        $sql = $pdo->prepare(
            'SELECT fish_name FROM fish NATURAL INNER JOIN result where how_id=? AND place_id=? AND season_id=?');
        $sql->execute([$_REQUEST['how_id'],$_REQUEST['place_id'],$_REQUEST['season_id']]);
        foreach($sql as $result){ ?>
        <li><?= $result['fish_name'] ?></li>
            </table>
        <?php } ?>
        <form class=form action="insert.php">
            <input type="submit" value="追加" method="post" name="select">
        </form>
</div>
<?php require 'footer.php'; ?>