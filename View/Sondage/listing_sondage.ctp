<div class="container">
    <table class="table">
        <tbody>
            <h3>Liste des sondages</h3>
            <?php if(!empty($last)): ?>
                <hr>
                <div class="alert alert-warning">
                    <?php 
                    foreach($last as $data): 
                        $startdate = $data['expireDate'];
                        $expire = strtotime($startdate. '+0days');
                        $today = strtotime("today midnight");
                        $isExpired = false;
                        if($today >= $expire){
                        $isExpired = true;
                        } else {
                        $isExpired = false;
                        }
                    ?>
                        <h4>NOUVEAU SONDAGE ! <?php if($isExpired){ ?>(Expiré)<?php }?></h4>
                        <span><?= $data['question']; ?></span>
                        <hr>
                        <a class="btn btn-primary btn-block" href="<?= $this->Html->url(array('controller' => 'Sondage','plugin' => "Sondage", 'admin' => false, 'action' => 'index', $data['id'])) ?>"><i class="fa fa-edit"></i> Répondre</a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>État</th>
                        <th>Date de publication</th>
                        <th>Date d'expiration</th>
                        <th>Actions</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($datas as $data):
                            $startdate = $data['Sondage']['expireDate'];
                            $expire = strtotime($startdate. '+0days');
                            $today = strtotime("today midnight");
                            $isExpired = false;
                            if($today >= $expire){
                              $isExpired = true;
                            } else {
                              $isExpired = false;
                            }
                        ?>
                        <tr>
                            <td><?= $data['Sondage']['question']; ?></td>
                            <td><?php if($isExpired){ ?><span class="label label-danger">Expiré</span><?php }else{ ?><span class="label label-success">Actif</span><?php }?></td>
                            <td><?= date('d/m/Y à H:m:s', strtotime($data['Sondage']['date'])); ?></td>
                            <td><?php if(!empty($data['Sondage']['expireDate'])): echo date('d/m/Y', strtotime($data['Sondage']['expireDate'])); else: echo "Erreur"; endif; ?></td>
                            <td><a class="btn btn-primary btn-block" href="<?= $this->Html->url(array('controller' => 'Sondage','plugin' => "Sondage", 'admin' => false, 'action' => 'index', $data['Sondage']['id'])) ?>"><i class="fa fa-eye"></i> Voir le sondage</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </tbody>
    </table>
</div>