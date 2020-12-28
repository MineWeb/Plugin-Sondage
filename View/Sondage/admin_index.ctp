<section class="content">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Configurer les sondages</h2>
            </div>
            <div class="card-body">
                <a class="btn btn-primary" href="<?= $this->Html->url(array('plugin' => 'Sondage', 'admin' => true, 'controller' => 'Sondage', 'action' => 'create')) ?>">Créer un sondage</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Question posée</th>
                            <th>URL</th>
                            <th>Date de publication</th>
                            <th>Date d'expiration</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($sondages as $sd): ?>
                            <tr>
                                <td><?= htmlspecialchars($sd['Sondage']['question']); ?></td>
                                <td><a href="<?= $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$this->Html->url(array('plugin' => 'Sondage', 'admin' => false, 'controller' => 'Sondage', 'action' => 'index', $sd['Sondage']['id'])) ?>"><?= $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$this->Html->url(array('plugin' => 'Sondage', 'admin' => false, 'controller' => 'Sondage', 'action' => 'index', $sd['Sondage']['id'])) ?></a></td>
                                <td><?= date('d/m/Y à H:m:s', strtotime($sd['Sondage']['date'])); ?></td>
                                <td><?= date('d/m/Y', strtotime($sd['Sondage']['expireDate'])); ?></td>
                                <td>
                                <div class="btn-group">
                                    <a href="<?= $this->Html->url(array('plugin' => 'Sondage', 'admin' => true, 'controller' => 'Sondage', 'action' => 'delete', $sd['Sondage']['id'])) ?>" class="btn btn-danger">Supprimer</a>
                                </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
