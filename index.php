<?php

require_once('src/Deal.php');
require_once('src/PotentialPlay.php');
require_once('src/Card.php');

$showDetail = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deal = new Deal($_POST);
    $deal->determinePossiblePlays();

    $showDetail = $_POST['showDetail'];
}

?>

<!doctype html>
<html>
<head>
    <title>Cribbage Discard Coach</title>
    <link rel="stylesheet" href="../cobaltandcurry/css/bootstrap.min.css" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<div class="container">
    <h1>Cribbage Discard Coach</h1>
    <form method="post" class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="card">Dealt Cards</label>
            <div class="controls">
                <input type="text" name="card0" class="span1" value="<?= isset($_POST['card0']) ? $_POST['card0'] : '2C' ?>" />
                <input type="text" name="card1" class="span1" value="<?= isset($_POST['card1']) ? $_POST['card1'] : '3C' ?>" />
                <input type="text" name="card2" class="span1" value="<?= isset($_POST['card2']) ? $_POST['card2'] : '4D' ?>" />
                <input type="text" name="card3" class="span1" value="<?= isset($_POST['card3']) ? $_POST['card3'] : '5H' ?>" />
                <input type="text" name="card4" class="span1" value="<?= isset($_POST['card4']) ? $_POST['card4'] : '5C' ?>" />
                <input type="text" name="card5" class="span1" value="<?= isset($_POST['card5']) ? $_POST['card5'] : 'JC' ?>" />
                <span class="help-block">Type card and suit. Examples: 5H, 10C, AS, JH</span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="crib">Crib</label>
            <div class="controls">
                <label class="radio">
                    <input type="radio" name="crib" value="self" checked>
                    My crib
                </label>
                <label class="radio">
                    <input type="radio" name="crib" value="pone">
                    Pone's crib
                </label>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <label class="checkbox">
                    <input type="hidden" name="showDetail" value="0" />
                    <input type="checkbox" name="showDetail" <?= $showDetail ? 'checked="checked"' : '' ?> />
                    Show detailed hand information
                </label>
                <button type="submit" class="btn btn-success">Help Me</button>
            </div>
        </div>
    </form>

    <?php if (isset($deal->possible_plays)): ?>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Hold</th>
                    <th>Discard</th>
                    <?php if ($showDetail): ?><th>Hands</th><?php endif; ?>
                    <th>Average Hand (Self)</th>
                    <th>Average Hand (Opponent)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($deal->possible_plays as $play): ?>
                    <tr>
                        <td><?= $play->getHolds() ?></td>
                        <td><?= $play->getDiscards() ?></td>
                        <?php if ($showDetail): ?>
                        <td>
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th>starter</th>
                                        <th>fifteens</th>
                                        <th>pairs</th>
                                        <th>runs</th>
                                        <th>frequency</th>
                                        <th>total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($play->hands as $hand): ?>
                                        <tr>
                                            <td><?= $hand['starter'] ?></td>
                                            <td><?= $hand['fifteens'] ?></td>
                                            <td><?= $hand['pairs'] ?></td>
                                            <td><?= $hand['runs'] ?></td>
                                            <td><?= $hand['frequency'] ?></td>
                                            <td><?= $hand['score'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td>
                        <?php endif; ?>
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
