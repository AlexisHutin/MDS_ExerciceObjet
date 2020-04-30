<?php
/**
 * CONSTANTES
 */
define('SEPARATEUR', '<br /><br /><hr /><br />'); // Pour séparer les pages les unes avec les autres

define('RACE_VAMPIRE', 'vampire');
define('RACE_LOUP_GAROUX', 'loup-garoux');
define('RACE_SORCIER', 'sorcier');
define('RACE_ZOMBIE', 'zombie');


/**
 * Objet Vampire
 */
class Vampire
{
	// Attributs
	public $race = RACE_VAMPIRE;
	public $clan = 'Ferinis';

	public $prenom = 'Carmela';
	public $age = 99;

	public $force;
	public $agilite;
	public $mechancete;
	public $magie;
	 
	public $stockDeSang = 0;
	public $soifDeSang = 0; // Va de 0 à 10. 0 => Pas en manque. 10 => En manque total
	public $pointsDeVie = 100;

	public $inventaire = [];
	public $euros = 7500;

	public $journal = [];
	public $artefacts = [];
	public $etape = [];
	
	// Constructeur (à coder)
	public function __construct()
	{
		$this->force = rand(12, 20);
		$this->agilite = rand(7, 15);
		$this->mechancete = rand(7, 20);
		$this->magie = rand(5, 15);
	}

	// Méthodes
    public function puissance(){ //calcul de la puissance du perso
        return $this->force + $this->magie + $this->agilite;
	}
	
	// Calcul de la force courante, en fonction de la soif de sang
	public function forceCourante()
	{
		switch (true) {
			case $this->soifDeSang == 10 : // Si la soif de sang est supérieure à 3, alors on gagne 1 pt de force
				return $this->force + 3;
			break;
			
			case $this->soifDeSang > 6 : // Si la soif de sang est supérieure à 6, alors on gagne 2 pts de force
				return $this->force + 2;
			break;

			case $this->soifDeSang > 3 : // Si la soif de sang est de 10, alors on gagne 3 pts de force
				return $this->force + 1;
			break;

			default:
		}

		return $this->force;
	}

	public function agiliteCourante()
	{
		switch (true) {
			case $this->soifDeSang > 7 : // Si la soif de sang est supérieure à 7, alors on perd 2 pts d'agilité
				return $this->agilite - 2;
				break;
			
			case $this->soifDeSang > 4 : // Si la soif de sang est supérieure à 4, alors on perd 1 pt d'agilité
				return $this->agilite - 1;
				break;
		}
	}

	public function mechanceteCourante()
	{
		$pointsMechant = $this->mechancete;
			
		switch (true) {
			case $this->soifDeSang > 8 : // Si la soif de sang est supérieure à 8, alors on gagne 2 pts de méchanceté
				$pointsMechant += 2;
				break;

			case $this->soifDeSang > 5 : // Si la soif de sang est supérieure à 5, alors on gagne 1 pt de méchanceté
				$pointsMechant += 1;
				break;
		}
		
		if ($this->pointsDeVie < 50){ // De plus, si les pointsDeVie sont inférieurs à 50, on gagne 1 pt de méchanceté
			$pointsMechant += 1;
		}

		return $pointsMechant;
	}

	public function conversionDollarsEuros($sommeEnDollars)
	{
		// 1 Euro = 1.24074 U.S. dollars
		// La banque prend une commission de 1€ sur chaque transaction
		return round((($sommeEnDollars / 1.24074) - 1), 2);
	}

	public function conversionPoundsEuros($sommeEnPounds)
	{
		// 1 Euro = 0.884473909 British pounds
		// La banque prend une commission de 1€ sur chaque transaction
		return round((($sommeEnPounds / 0.884473909) - 1), 2);
	}

	 //ajout/retrait d'or
	public function laBourse($signe, $montant)
	{
		if($signe == '+'){
			$this->euros += $montant;
			$this->ajoutActionAEtape("Ajout de $montant euros");
		}
		else{
			$this->euros -= $montant;
			$this->ajoutActionAEtape("Retrait de $montant euros");
		}
		return;
	}

	 //ajout d'item a l'inventaire
	public function ajoutInventaire($item)
	{
		$this->inventaire[] = $item;
		$this->ajoutArtefactAEtape("Ajout de : $item");
		return;
	}

	public function modifieSoifDeSang($signe, $valeur){ //ajout/retrait de points a la soif de sang
		if ($signe == '+') {
			$this->soifDeSang += $valeur;
			$this->ajoutActionAEtape("La soif de sang augmente de $valeur");
		} else {
			$this->soifDeSang -= $valeur;
			$this->ajoutActionAEtape("La soif de sang diminue de $valeur");
		}

		return true;
	}

	public function dateEtVilleEtape($date, $ville){ //met la date et la ville dans le tab étape du jour
		$this->etape['date'] = $date;
		$this->etape['ville'] = $ville;
		
		return;
	}

	public function ajoutActionAEtape($action){ //ajoute une action au tab etape
		$this->etape['action'][] = $action;
		return;
	}

	public function ajoutArtefactAEtape($artefact){ //ajoute l'ajout d'item au tab etape
		$this->etape['artefacts'][] = $artefact;
		return;
	}

	public function ajoutEtapeAJournal(){ // ajoute le tab etape au tab journal
		$this->journal[] = $this->etape;
		return;
	}

	public function resetEtape(){ // reset du tab etap pour une nouvelle journée
		$this->etape = [];
		return;
	}

	public function resetSoifDeSang(){ // reset les points de soif de sang
		$this->soifDeSang = 0;
		$this->ajoutActionAEtape("La soif de sang redescend à 0");
		return;
	}

	 // ajout/retrait de points de vie
	public function altererPointDeVie($signe, $valeur)
	{
		if ($signe == '+') {
			$this->pointsDeVie += $valeur;
			$this->ajoutActionAEtape("Elle regagne $valeur points de vie");
			return true;
		}

		if ($signe == '-') {
			$this->pointsDeVie -= $valeur;
			$this->ajoutActionAEtape("Elle perd $valeur points de vie");
		}
		return;
	}

	public function mortDuVampire(){ // a la mort du perso retranscrit le journal et stop le script courant
		$this->ajoutActionAEtape('Elle meurt ...');
		$this->ajoutEtapeAJournal();
		echo $this->transcriptionDuJournal();
		exit();
	}

	public function transcriptionDuJournal() // Parcourir le journal pour créer une chaine de caractères complètes 
	{
		$out = '';
		foreach ($this->journal as $journal) {
			$out .= '<hr/> <br/>';
			$out .= $journal['date'] . '<br/>';
			$out .= $journal['ville'] . '<br/>';

			foreach($journal['action'] as $action){
				$out .= $action . '<br/>';
			}

			if (isset($journal['artefacts'])){
				$out .= 'Carmela a obtenu les artefacts suivants : ' . '<br/>';
				foreach($journal['artefacts'] as $artefacts){
					$out .= $artefacts . '<br/>';
				}
			}
			
		}

		return $out;
	}


}