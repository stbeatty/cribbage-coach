<?php

require_once('class.deal.php');

// Tests
// $play = new PotentialPlay();
// $play->discard(2);
// $play->discard('J');
// $play->keep(array(5,3,4,5));

// assert(6 == $play->countRuns(array(5,3,4,5,'A')));
// assert(8 == $play->countRuns(array(2,3,4,5,5)));
// assert(12 == $play->countRuns(array(5,3,4,5,3)));
// assert(6 == $play->countRuns(array(5,3,4,5,7))); //6
// var_dump($play->getAverageHand());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deal = new Deal($_POST);
    $deal->determinePossiblePlays();
}

?>

<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="../cobaltandcurry/css/bootstrap.min.css" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<div class="container">
    <h1>Enter card values</h1>
    <form method="post" class="form-inline">
        <input type="text" name="card1" class="span1" value="<?= isset($_POST['card1']) ? $_POST['card1'] : '' ?>" />
        <input type="text" name="card2" class="span1" value="<?= isset($_POST['card2']) ? $_POST['card2'] : '' ?>" />
        <input type="text" name="card3" class="span1" value="<?= isset($_POST['card3']) ? $_POST['card3'] : '' ?>" />
        <input type="text" name="card4" class="span1" value="<?= isset($_POST['card4']) ? $_POST['card4'] : '' ?>" />
        <input type="text" name="card5" class="span1" value="<?= isset($_POST['card5']) ? $_POST['card5'] : '' ?>" />
        <input type="text" name="card6" class="span1" value="<?= isset($_POST['card6']) ? $_POST['card6'] : '' ?>" />
        <button type="submit" class="btn">Analyze</button>
    </form>

    <?php if (isset($deal->possible_plays)): ?>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Hold</th>
                    <th>Discard</th>
                    <th>Average Hand (Self)</th>
                    <th>Average Hand (Opponent)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($deal->possible_plays as $play): ?>
                    <tr>
                        <td><?= $play->getHolds() ?></td>
                        <td><?= $play->getDiscards() ?></td>
                        <td><?= $play->getExpectedAverageSelf() ?></td>
                        <td><?= $play->getExpectedAverageOpponent() ?></td>
                    </tr>
                <?php endforeach; ?>
            <tbody>
        </table>
    <?php endif; ?>

</div>
</body>
</html>
