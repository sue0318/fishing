<?php require 'header.php'; 
    $pdo = new PDO('mysql:host=localhost;dbname=fishing;charset=utf8','staff','password');
?>

<div class=form>
    <form action="index.php" class="select" method="post">

            <div class=fish>
                <h1>おさかな</h1>
                <input type="text" class="bottom" name="fish">
            </div>
            
        <div class=checkbox>
            <div class="select">
                <h1>場所</h1>
                <?php
                    $place = $pdo->query('SELECT * FROM place');
                    $miyazaki = $place->fetchAll(PDO::FETCH_ASSOC);
                    foreach($miyazaki as $row){
                        echo '<input type="checkbox" name="place[]" value= "'.$row['miyazaki'].'">';
                        echo '<input type="hidden" name="place_id[]" value= "'.$row['place_id'].'">';
                        echo $row['miyazaki'];
                        echo '<br>';
                    }
                ?>
                <input type="text" class="bottom" name="miyazaki">
            </div>
                    
            <div class="select">
                <h1>釣り方</h1>
                <?php
                    $how = $pdo->query('SELECT * FROM how');
                    $tool = $how->fetchAll(PDO::FETCH_ASSOC);
                    foreach($tool as $row){
                        echo '<input type="checkbox" name="how[]" value= "'.$row['tool'].'">';
                        echo '<input type="hidden" name="how_id[]" value= "'.$row['how_id'].'">';
                        echo $row['tool'];
                        echo '<br>';
                    }
                ?>
                <input type="text" class="bottom" name="tool">
            </div>

            <div class="select">
                <h1>時期</h1>
                <?php
                    $season = $pdo->query('SELECT * FROM season');
                    // $month = $season->fetchAll(PDO::FETCH_ASSOC);
                    foreach($season as $row){
                        echo '<input type="checkbox" name="season[]" value= "'.$row['month'].'">';
                        echo '<input type="hidden" name="season_id[]" value= "'.$row['season_id'].'">';
                        echo $row['month'];
                        echo '<br>';
                    }
                ?>
            </div>
        </div>
        <input type="submit"  class="bottom" value="追加">
    </form> 
</div>

<?php require 'footer.php'; 