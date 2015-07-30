<?php
switch ($modx->event->name) {
  case 'OnHandleRequest':
    if ($modx->context->key == 'mgr') {
      return;
    }

    $qustha = $modx->getService('qustha','qustha',$modx->getOption('qustha_core_path',null,$modx->getOption('core_path').'components/qustha/', true).'model/qustha/');
    if (!($qustha instanceof qustha)) return '';

    $qustha->run();

    // save data to user's 'extended' field
//    if (($user = $modx->getAuthenticatedUser())) {
//      $profile = $user->getOne('Profile');
//      $extended = json_decode($profile->get('extended'), 1);
//      if (!isset($extended['qustha'])) {$extended['qustha'] = array();}
//      $date = date('d.m.Y');
//      $extended['qustha'][$date] = array();
//      $vars = $qustha->getVars('storage');
//      foreach ($vars as $k=>$v) {
//        $extended['qustha'][$date][$k] = $v;
//      }
//      $profile->set('extended', json_encode($extended));
//      $profile->save();
//    }
    break;
}
