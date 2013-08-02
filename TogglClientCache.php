<?php
class TogglClientCache extends TogglSmartCache{

    public function getDataFromSource(){
        $clients = TogglClient::getClientsVisibleToUser();
        return $clients;
    }
}