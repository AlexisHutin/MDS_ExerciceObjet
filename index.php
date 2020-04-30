<?php
// Appel de l'objet
include('Vampire.php');
include('Sorcier.php');
 
 /**
  * Commencer par coder le constructeur
  */
$vampire = new Vampire();

 /**
  * HISTOIRE : Votre héroïne Vampire vient d'une famille célèbre de vampires.
  * Pour fêter ses 100 ans, elle doit parcourir le monde à la recherche de 3 artefacts
  * Elle ne peut voyager que la nuit.
  * Ses compétences sont altérées par sa soif de sang
  * Elle doit consigner ses faits et gestes dans un journal, avec à chaque fois : 
  *		- date et heure
  * 	- ville
  * 	- actions significatives
  * 	- artefact trouvé, si oui lequel
  */
 /**
  * NUIT 1 : Paris, le 11 Février 2018
  */
$vampire->dateEtVilleEtape('11/02/2018', 'Paris');
 /**
  * Votre héroïne Vampire quitte son domaine, avant de partir, son frère lui confie 2 doses
  * de sang et 5.000€. Elle monte dans sa voiture et rejoint l'aéroport.
  */
$vampire->ajoutActionAEtape('Elle quitte sont domaine et son frère lui donne quelque chose pour son départ');
$vampire->ajoutInventaire('2 doses de sang');
$vampire->laBourse('+', 5000);
 /**
  * Sur le parking de l'aéroport, elle paye 100€ pour pouvoir y laisser sa voiture 1 mois.
  * Elle tombe sur un sorcier qu'elle connait bien. Celui-ci décide de l'aider dans sa quête
  * et lui donne alors un premier objet :
  * 	- si sa magie est inférieure à 10, alors elle reçoit l'objet : "dague de sorcier"
  *		- si sa magie est égale ou supérieure à 10, alors elle reçoit l'objet : "poil de loup-garou"
  */
$vampire->ajoutActionAEtape('Elle loue une place de parking pour un mois');
$vampire->laBourse('-', 100);

$vampire->ajoutActionAEtape('Elle rencontre un sorcier qui lui donne un artefact');
if ($vampire->magie >= 10){
    $vampire->ajoutInventaire('poil de loup-garou');
}
else{
    $vampire->ajoutInventaire('dague de sorcier');
}
 /**
  * Un billet d'avion acheté 150€ et elle s'envole pour Londres
  * Durant le vol, sa soif de sang augmente de 1
  */
$vampire->ajoutActionAEtape('Elle prend un billet d\'avion pour Londre');
$vampire->laBourse('-', 150);
$vampire->modifieSoifDeSang('+', 1);
 /**
  * Elle prend un taxi (10£) pour se rendre au VCoL (Vampire Club of London)
  * C'est un club spécial pour vampire, où on paye en fonction de sa force.
  * Le verre de sang coûte de base 20£.
  *		- Si la force du Vampire est de 10 ou +, il a 5£ de réduction
  *		- Si la force du Vampire est de 15 ou +, il a 5£ de réduction supplémentaire
  * Le verre de sang lui fait descendre sa soif de sang de 1 unité
  */
$vampire->ajoutActionAEtape('Elle prend un taxi pour se rendre au VCoL');
$vampire->laBourse('-', $vampire->conversionPoundsEuros(10));

$prixDuVerre = 20;
if ($vampire->force >= 10){
  $prixDuVerre -= 5;
}

if ($vampire->force >= 15){
  $prixDuVerre -= 5;
}
$vampire->ajoutActionAEtape('Elle prend un verre de sang');
$vampire->laBourse('-', $vampire->conversionPoundsEuros($prixDuVerre));
$vampire->modifieSoifDeSang('-', 1);
 /**
  * Retour à l'aéroport en taxi, pour 10£.
  * Elle prend un nouvel avion pour Los Angeles, pour 660£.
  * Durant le vol, sa soif de sang augmente de 2
  */
$vampire->ajoutActionAEtape('Elle prend un taxi pour retourner a l\'aéroport');
$vampire->laBourse('-', $vampire->conversionPoundsEuros(10));
$vampire->ajoutActionAEtape('Elle prend un billet pour LA');
$vampire->laBourse('-', $vampire->conversionPoundsEuros(660));
$vampire->modifieSoifDeSang('+', 2);
 /**
  * Fin de la journée, remplir le journal
  */

$vampire->ajoutEtapeAJournal();
$vampire->resetEtape();



 /**
  * NUIT 2 : Désert du Nevada, le 12 Février 2018
  */
$vampire->dateEtVilleEtape('12/02/2018', 'Désert du Nevada');

 /**
  * Elle loue une voiture de sport pour se rendre dans le désert du Nevada, coût : 250$
  */
$vampire->ajoutActionAEtape('Elle loue une voiture');
$vampire->laBourse('-', $vampire->conversionDollarsEuros(250));
 /**
  * Arrivé dans le désert, elle doit se rendre dans une grotte millénaire.
  * Si son agilité est inférieure à 10, alors elle va mettre + de temps et sa soif 
  * de sang augmente de 1
  */
$vampire->ajoutActionAEtape('Elle est arriver dans le desert et se rend dans une grotte');
if ($vampire->agilite < 10){
  $vampire->modifieSoifDeSang('+', 1);
}
 /**
  * Dans la grotte, elle est attaquée par un sorcier
  * Le sorcier a les caractéristiques suivantes :
  * 	- Force : rand(3,5)
  * 	- Magie : rand(10,15)
  * 	- Agilité : rand(5,7)
  * 
  * Les points de combats du Sorcier sont égaux à la somme de ces 3 caractéristiques.
  *
  * Pour Carmela, ses points de combats sont égaux à la somme de ses 3 mêmes caractéristiques.
  * 
  * Si PtC du sorcieur > PtC Carmela, alors elle perd autant de points de vie (x5).
  * Exemple : Le sorcier a 21. Si Carmela a 19, alors elle perd 2 x5 => 10pts de vie.
  *
  * Si Carmela a + de point de combat, alors elle le terrasse et gagne l'artefact "Pierre du désert"
  * Si les points de vie tombent à zéro, elle meurt.
  */

$sorcier = new Sorcier();
$vampire->ajoutActionAEtape('Elle est attaquer par un sorcier !');
if ($sorcier->puissance() > $vampire->puissance()){
  $vampire->altererPointDeVie('-', ($sorcier->puissance() - $vampire->puissance()) * 5);
  
  if ($vampire->pointsDeVie <= 0){
    $vampire->mortDuVampire();
  } else{
    $vampire->ajoutActionAEtape('Elle fuit !');
  }
}
else{
  $vampire->ajoutActionAEtape('Elle terrasse le Sorcier !');
  $vampire->ajoutInventaire('Pierre du désert');
}

 /**
  * Retour à l'aéroport.
  * Elle achète un billet d'avion pour l'Afrique du sud, coût : 700$.
  */
$vampire->ajoutActionAEtape('Elle achète un billet d\'avion');
$vampire->laBourse('-', $vampire->conversionDollarsEuros(700));

 /**
  * Dans l'avion, son voisin relou n'arrête pas de lui parler...
  * Si sa méchanceté est de 15 ou plus, elle le tue dans les toilettes de l'avion et le vide de son sang.
  * 	Sa soif de sang retombe alors à zéo.
  * Si sa méchanceté est entre 10 et 14. Elle l'assome simplement, et lui prend un peu de sang.
  *		Sa soif de sang tombe de 2 unités.
  *
  * Sinon elle le laisse parler toute la nuit, mais sa soif de sang augmente donc d'1 unité.
  */
if ($vampire->mechancete >= 15){
  $vampire->ajoutActionAEtape('Elle tue son voisin relou');
  $vampire->resetSoifDeSang();
  
}
elseif ($vampire->mechancete > 10 && $vampire->mechancete <= 14 ){
  $vampire->ajoutActionAEtape('Elle assome sont voisin relou et lui prend un peu de sang');
  $vampire->modifieSoifDeSang('-', 2);
  
}
else{
  $vampire->ajoutActionAEtape('Elle support son voisin relou toute la nuit');
  $vampire->modifieSoifDeSang('+', 1);
}
 /**
  * Fin de la journée, remplir le journal
  */

  
$vampire->ajoutEtapeAJournal();
$vampire->resetEtape();
  
  
/*echo '<pre>';
print_r($vampire->journal);
echo '</pre>';*/
  
echo $vampire->transcriptionDuJournal();



  /**
   * RETRANSCRIPTION DU JOURNAL
   */
  //echo $vampire->transcriptionDuJournal($vampire->$journal);


  /**
   * Aller + loin, créez un objet dédié pour le Journal
   */
?>
<html>