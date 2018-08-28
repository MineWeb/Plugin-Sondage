<?php
class SondageController extends AppController {

  public function clear_cache() {
    Cache::clear();
    clearCache();

    $files = array();
    $files = array_merge($files, glob(CACHE . '*')); // remove cached css
    $files = array_merge($files, glob(CACHE . 'css' . DS . '*')); // remove cached css
    $files = array_merge($files, glob(CACHE . 'js' . DS . '*'));  // remove cached js           
    $files = array_merge($files, glob(CACHE . 'models' . DS . '*'));  // remove cached models           
    $files = array_merge($files, glob(CACHE . 'persistent' . DS . '*'));  // remove cached persistent           

    foreach ($files as $f) {
        if (is_file($f)) {
            unlink($f);
        }
    }

    if(function_exists('apc_clear_cache')):      
    apc_clear_cache();
    apc_clear_cache('user');
    endif;

    $this->set(compact('files'));
  }

  public function updateSQLSondage(){
    $this->loadModel('Sondage.Sondage');
    if(empty($this->Sondage->query("SHOW COLUMNS FROM `sondage__question` LIKE 'expireDate'"))):
      $this->Sondage->query("ALTER TABLE sondage__question ADD expireDate VARCHAR(255)");
      $this->clear_cache();
    endif;
  }

  function listingSondage() {
    $this->updateSQLSondage();
    $this->loadModel('Sondage.Sondage');
    $datas = $this->Sondage->find('all', array('fields' => array('Sondage.question', 'Sondage.id', 'Sondage.date', 'Sondage.expireDate'), 'order' => array('Sondage.date DESC')));
    $last = $this->Sondage->find('first', array('fields' => array('Sondage.question', 'Sondage.id', 'Sondage.date', 'Sondage.expireDate'), 'order' => array('Sondage.date DESC')));
    $this->set(compact('last', 'datas'));
    $this->set('title_for_layout', "Liste des sondages");
  }

  function index($id) {
    $this->updateSQLSondage();
    if(empty($id) || !is_numeric($id))
      throw new ForbiddenException();
    $sondage = $this->Sondage->find('first', array('conditions' => array("Sondage.id" => array($id))));
    $startdate = $sondage['Sondage']['expireDate'];
    $expire = strtotime($startdate. '+0days');
    $today = strtotime("today midnight");
    $isExpired = false;
    if($today >= $expire){
      $isExpired = true;
    } else {
      $isExpired = false;
    }
    if(empty($sondage))
      throw new ForbiddenException();
    $sondage['Sondage']['response'] = json_decode($sondage['Sondage']['response'], true);
    $isAlreadyVoting = false;
    $voteInteger = 0;
    foreach($sondage['Sondage']['response'] as $k => $resp):
      foreach($resp['votes'] as $vote):
        if($vote['userid'] == $this->User->getKey('id')):
          $isAlreadyVoting = true;
        endif;
        $voteInteger++;
      endforeach;
    endforeach;
    $this->set(compact('sondage', 'isExpired', 'isAlreadyVoting', 'voteInteger'));
    $this->set('title_for_layout', "Sondage | ".$sondage['Sondage']['question']);
  }

  function ajax_votes() {
    $this->updateSQLSondage();
    if (!$this->isConnected)
      throw new BadRequestException();
    $this->loadModel('Sondage.Sondage');
    if (!$this->request->is('post'))
      throw new BadRequestException();
    $this->loadModel('Sondage.Sondage');
    $voteSelect = $this->request->data['respSelect'];
    if(empty($voteSelect))
      return $this->sendJSON(['statut' => false, 'msg' => "Vous devez remplir tout les champs !"]);
    //SYS VOTE USER
    $sondage = $this->Sondage->find('first', array('fields' => array('Sondage.id', 'Sondage.response'), 'conditions' => array("Sondage.id" => array($this->request->data['id_sondage']))));
    if(empty($sondage))
      throw new ForbiddenException();
    $sondage['Sondage']['response'] = json_decode($sondage['Sondage']['response'], true);
    for($i_while_rs = 0; $i_while_rs < count($sondage['Sondage']['response']); $i_while_rs++):
      if($voteSelect == $sondage['Sondage']['response'][$i_while_rs]['id']):
        array_push($sondage['Sondage']['response'][$i_while_rs]['votes'], array('userid' => $this->User->getKey('id')));
      endif;
    endfor;
    $sondage['Sondage']['response'] = json_encode($sondage['Sondage']['response']);
    $this->Sondage->read(null, $sondage['Sondage']['id']);
    $this->Sondage->set(array(
      "response" => $sondage['Sondage']['response']
    ));
    $this->Sondage->save();
    return $this->sendJSON(['statut' => true, 'msg' => "Votre réponse a bien été pris en compte ! "]);
  }

  function admin_index() {
    $this->updateSQLSondage();
    if($this->isConnected AND $this->User->isAdmin()):
      $this->layout = "admin";
      $sondages = $this->Sondage->find('all', array('order' => array('Sondage.date DESC')));
      foreach($sondages as $k => $v):
        $sondages[$k]['Sondage']['response'] = json_decode($sondages[$k]['Sondage']['response'], true);
        $sondages[$k]['Sondage']['vote'] = json_decode($sondages[$k]['Sondage']['vote'], true);
      endforeach;
      $this->set(compact('sondages'));
      $this->set('title_for_layout', "Gérer les sondages");
    else:
      throw new ForbiddenException();
    endif;
  }

  function admin_create() {
    $this->updateSQLSondage();
    if($this->isConnected AND $this->User->isAdmin()):
      $this->layout = "admin";
      $this->set('title_for_layout', "Créer un sondage");
    else:
      throw new ForbiddenException();
    endif;
  }

  function admin_ajax_create() {
    $this->updateSQLSondage();
    if($this->isConnected AND $this->User->isAdmin()):
      $this->loadModel('Sondage.Sondage');
      if(!$this->request->is('post'))
        throw new BadRequestException();
      if(empty($this->request->data["question"]))
        return $this->sendJSON(['statut' => false, 'msg' => "Vous devez remplir tout les champs !"]);
      if(empty($this->request->data["dateExpire"]))
        return $this->sendJSON(['statut' => false, 'msg' => "Vous devez remplir tout les champs !"]);
      $respPre = array();
      if(count($this->request->data['data_resp']) == 1)
        return $this->sendJSON(['statut' => false, 'msg' => "Vous devez ajouter plus d'une question tout les champs !"]);
      $i_rs = 1;
      foreach($this->request->data['data_resp'] as $rs):
        if(empty($rs))
          return $this->sendJSON(['statut' => false, 'msg' => "Vous devez remplir tout les champs !"]);
        $voteInit = array();
        array_push($respPre, array("id" => "$i_rs", "subject" => $rs, "votes" => $voteInit, "colorCustom" => ""));
        $i_rs++;
      endforeach;
      $responseData = json_encode($respPre);
      $expireDate = $this->request->data["dateExpire"];
      $this->Sondage->set(array(
        "question" => $this->request->data["question"],
        "response" => $responseData,
        "expireDate" => $expireDate
      ));
      $this->Sondage->save();
      return $this->sendJSON(['statut' => true, 'msg' => "Sondage créer avec succès !"]);
    else:
      throw new ForbiddenException();
    endif;
  }

  function admin_delete($id) {
    $this->updateSQLSondage();
    if($this->isConnected AND $this->User->isAdmin()):
      $this->loadModel('Sondage.Sondage');
      if(empty($id))
        throw new ForbiddenException();
      $sondage = $this->Sondage->find('first', array('conditions' => array("Sondage.id" => array($id))));
      if(empty($sondage))
        throw new ForbiddenException();
      $this->Sondage->delete($id);
      $this->redirect('/admin/sondage');
    else:
      throw new ForbiddenException();
    endif;
  }

}
