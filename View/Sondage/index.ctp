<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <h3>Sondage</h3>
            <h4><i class="fa fa-question-circle"></i> <?= htmlspecialchars($sondage['Sondage']['question']); ?></h4>
            <hr>
            <?php if($isExpired): ?>
                <div class="alert alert-warning">Le sondage a expiré.</div>
            <?php elseif(!$isConnected): ?>
                <div class="alert alert-danger">Vous devez être connecté pour voter.</div>
            <?php elseif($isAlreadyVoting): ?>
                <div class="alert alert-success">Vous avez déjà voté sur ce sondage.</div>
                <hr>
                <?php foreach($sondage['Sondage']['response'] as $rs): ?>
                    <b><?= $rs['subject']; ?></b> (<?= count($rs['votes']); ?> Votes) <br />
                <?php endforeach; ?>
            <?php else: ?>
                <h4>Pour voter, séléctionnez votre réponse ci-dessous: </h4>
                <form method="post" class="form-horizontal" data-ajax="true" data-redirect-url="./<?= $sondage['Sondage']['id']; ?>" action="<?= $this->Html->url(array('controller' => 'Sondage','plugin' => "Sondage", 'admin' => false, 'action' => 'ajax_votes')) ?>">
                    <input type="hidden" name="id_sondage" value="<?= $sondage['Sondage']['id']; ?>">
                    <?php foreach($sondage['Sondage']['response'] as $rs): ?>
                        <input type="radio" name="respSelect" value="<?= $rs['id']; ?>">
                        <label><?= $rs['subject']; ?> (<?= count($rs['votes']); ?> Votes)</label><br />
                    <?php endforeach; ?>
                    <br />
                    <button type="submit" class="btn btn-primary">Répondre</button>
                </form>
            <?php endif; ?>
        </div>
        <div class="col-sm-6">
            <?php if($voteInteger < 1): ?>
                <div class="alert alert-warning">Aucu vote n'a été trouvé. Le résultat ne peut donc pas être affiché</div>
            <?php else: ?>
                <div id="container" style="min-width: 310px; max-width: 800px; height: 400px; margin: 0 auto"></div>
            <?php endif; ?> 
        </div>
    </div>
</div>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>

// Build the chart
Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Résultat du sondage'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [
        <?php foreach($sondage['Sondage']['response'] as $rs): ?>
            {
                name: '<?= $rs['subject']; ?>',
                y: <?= count($rs['votes']); ?>
            },
        <?php endforeach; ?>
        ]
    }]
});
</script>