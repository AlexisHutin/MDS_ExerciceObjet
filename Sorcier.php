<?php
class Sorcier
{
    // Attributs -----------
    public $force;
    public $magie;
    public $agilite;

    // Constructeur ----------
    public function __construct(){
        $this->force = rand(3,5);
        $this->magie = rand(10,15);
        $this->agilite = rand(5,7);
    }

    // Méthodes Spécifiques -------------

    // Calcul de la puissance du perso
    public function puissance()
    { 
        return $this->force + $this->magie + $this->agilite;
    }
}
