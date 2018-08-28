<?php
//CLIENT
Router::connect('/sondage', array('controller' => 'Sondage', 'action' => 'listingSondage', 'plugin' => 'Sondage', 'admin' => false));
Router::connect('/sondage/q/*', array('controller' => 'Sondage', 'action' => 'index', 'plugin' => 'Sondage', 'admin' => false));
Router::connect('/sondage/ajax_votes', array('controller' => 'Sondage', 'action' => 'ajax_votes', 'plugin' => 'Sondage', 'admin' => false));
//ADMIN
Router::connect('/admin/sondage', array('controller' => 'Sondage', 'action' => 'index', 'plugin' => 'Sondage', 'admin' => true));
Router::connect('/admin/sondage/create', array('controller' => 'Sondage', 'action' => 'create', 'plugin' => 'Sondage', 'admin' => true));
Router::connect('/admin/sondage/ajax_create', array('controller' => 'Sondage', 'action' => 'ajax_create', 'plugin' => 'Sondage', 'admin' => true));
Router::connect('/admin/sondage/delete/*', array('controller' => 'Sondage', 'action' => 'delete', 'plugin' => 'Sondage', 'admin' => true));