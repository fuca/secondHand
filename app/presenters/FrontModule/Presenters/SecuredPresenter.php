<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;

/**
 * @author m.fucik
 */
abstract class SecuredPresenter extends BasePresenter {

    public function startup() {
        parent::startup();
        $user = $this->getUser();
        if (!$user->isLoggedIn()) {
            if ($user->getLogoutReason() === User::INACTIVITY) {
                //$this->flashMessage($this->tt("securityModule.loginControl.messages.outCosInactive"), self::FM_WARNING);
            }
            $backlink = $this->storeRequest();
            $this->redirect(':Security:Auth:in', ['backlink' => $backlink]);
        }
    }
}
