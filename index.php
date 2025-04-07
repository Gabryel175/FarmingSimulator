<?php include_once 'header.php'; ?>
<div class="navbar-secund">
  <div class="container-navbar mx-auto">
    <a href="" class="douazecilasuta active"><i class="fas fa-shopping-cart"></i>Categorii</a>
    <a href="rar.php" class="douazecilasuta"><i class="fa fa-car"></i>Registru Auto</a>
    <a href="banca.php" class="douazecilasuta"><i class="fa fa-dollar"></i>Unitate Bancara</a>
    <a href="primarie.php" class="douazecilasuta"><i class="fa fa-building"></i>Primarie</a>
    <a href="dashboard.php" class="douazecilasuta"><i class="fa fa-wrench" aria-hidden="true"></i>Panou de control</a>
  </div>
</div>
<div class="sub-navbar">
        <div class="row row-sub-navbar mx-auto">
          <div class="col col-1" id="submenu-trigger">
            <a href="#" class="link-hover" data-target="Tractoare"><img src="https://cdn-icons-png.flaticon.com/128/1112/1112402.png" alt="">Tractoare</a>
            <a href="#" class="link-hover" data-target="Combine"><img src="https://cdn-icons-png.flaticon.com/128/517/517500.png" alt="">Combine</a>
            <a href="#" class="link-hover" data-target="UtilajeSemanat"><img src="https://cdn-icons-png.flaticon.com/128/2853/2853165.png" alt="">Utilaje Semanat</a>
            <a href="#" class="link-hover" data-target="UtilajeRecoltat"><img src="https://cdn-icons-png.flaticon.com/128/4634/4634860.png" alt="">Utilaje Recoltat/Ierboase</a>
            <a href="#" class="link-hover" data-target="UtilajeFertilizat"><img src="https://cdn-icons-png.flaticon.com/128/1669/1669903.png" alt="">Utilaje Fertilizat</a>
            <a href="#" class="link-hover" data-target="EchipamenteAnimale"><img src="https://cdn-icons-png.flaticon.com/128/672/672716.png" alt="">Echipamente Animale</a>
            <a href="#" class="link-hover" data-target="EchipamenteForestiere"><img src="https://cdn-icons-png.flaticon.com/128/3353/3353143.png" alt="">Echipamente Forestiere</a>
            <a href="#" class="link-hover" data-target="EchipamenteManipulare"><img src="https://cdn-icons-png.flaticon.com/128/3172/3172343.png" alt="">Echipamente Manipulare</a>
            <a href="#" class="link-hover" data-target="Remorci"><img src="https://cdn-icons-png.flaticon.com/128/5113/5113972.png" alt="">Remorci</a>
            <a href="#" class="link-hover" data-target="Masini"><img src="https://cdn-icons-png.flaticon.com/128/4634/4634590.png" alt="">Masini</a>
            <a href="#" class="link-hover" data-target="Altele"><img src="https://cdn-icons-png.flaticon.com/128/10348/10348994.png" alt="">Altele</a>

            <div class="submenu" id="submenu-Tractoare">
                <h6 class="title_submenu">Meniu Tractoare</h6>
                <div class="container-fluid mx-auto">
                    <div class="row">
                        <div class="col-md">
                            <H6 class="text-center">Din joc : </H6>
                            <ul class="list-group">
                                <?php
                                    $caleFisier = "json-files/utilaje.json";
                                    $continutFisier = file_get_contents($caleFisier);
                                    $dateUtilaje = json_decode($continutFisier, true);
                                    $countLarge=$countMedium=$countSmall=0;
                                    $countGreutati=$countFrontLoadersA=0;
                                    foreach($dateUtilaje['utilaje_joc'] as $utilaj){
                                      if($utilaj['categorie']==='LargeTractors'){
                                        $countLarge++;
                                      }
                                      if($utilaj['categorie']==='MediumTractors'){
                                        $countMedium++;
                                      }
                                      if($utilaj['categorie']==='SmallTractors'){
                                        $countSmall++;
                                      }
                                      if($utilaj['categorie']==='Greutate'){
                                        $countGreutati++;
                                      }
                                      if($utilaj['categorie']==='FrontLoadersA'){
                                        $countFrontLoadersA++;
                                      }
                                    }
                                ?>
                                <li class="list-group-item">
                                  <a href="<?php echo 'shop_individual.php?choice=SmallTractors'; ?>">Tractoare Mici </a>
                                  <?php echo '<span class="point">' . $countSmall . '</span>'; ?>
                                </li>
                                <li class="list-group-item">
                                  <a href="<?php echo 'shop_individual.php?choice=MediumTractors'?>">Tractoare Medii </a>
                                  <?php echo '<span class="point">' . $countMedium . '</span>'; ?>
                                </li>
                                <li class="list-group-item">
                                  <a href="<?php echo 'shop_individual.php?choice=LargeTractors'?>">Tractoare Mari </a>
                                  <?php echo '<span class="point">' . $countLarge . '</span>'; ?>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md">
                            <h6 class="text-center">Moduri : </h6>
                            <ul class="list-group">
                                <li class="list-group-item"><a href="">Tractoare Moduri</a><?php echo '<span class="point-error">Indisponibil</span>'; ?></li>
                                <li class="list-group-item"><a href="">Tractoare Utilaje</a><?php echo '<span class="point-error">Indisponibil</span>'; ?></li>
                                <li class="list-group-item"><a href="">Tractoare Forestiere</a><?php echo '<span class="point-error">Indisponibil</span>'; ?></li>
                                <li class="list-group-item"><a href="">Tractoare Animale</a><?php echo '<span class="point-error">Indisponibil</span>'; ?></li>
                            </ul>
                        </div>
                        <div class="col-md">
                        <h6 class="text-center">Atasamente : </h6>
                            <ul class="list-group">
                               <li class="list-group-item">
                                  <a href="<?php echo 'shop_individual.php?choice=Greutate'?>">Greutati </a>
                                  <?php echo '<span class="point">' . $countGreutati . '</span>'; ?>
                                </li>
                                <li class="list-group-item">
                                  <a href="<?php echo 'shop_individual.php?choice=FrontLoadersA'?>">FrontLoaders</a>
                                  <?php echo '<span class="point">' . $countFrontLoadersA . '</span>'; ?>
                                </li>
                                <li class="list-group-item"><a href="">Snowing</a></li>
                                <li class="list-group-item"><a href="">Forestry</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <h6>Ori , vezi toate : <a href="<?php echo 'shop_individual.php?choice=ToateTractoare'?>">TOATE</a></h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  if (isset($dateUtilaje['branduri']) && is_array($dateUtilaje['branduri'])) {
                    $count_elemente=count($dateUtilaje['branduri']);
                    ?>  
                      <ul class="lista_de_branduri">
                    <?php
                      $index=0;
                      foreach ($dateUtilaje['branduri'] as $brand) {
                          if (isset($brand['pentru'])) {
                              $index++;
                              $categorii = explode(',', $brand['pentru']);
                              if (in_array('Tractoare', $categorii)) {
                                if($index<$count_elemente){
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].' , </li>';
                                }else{
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].'</li>';
                                }
                              }
                          }
                      }
                  } else {
                      echo "Array-ul sau cheia nu există în fișierul JSON.";
                  }
                ?>
                </ul>
            </div>
            <div class="submenu" id="submenu-Combine">
                <h6 class="title_submenu">Meniu Combine</h6>
                <div class="container-fluid mx-auto">
                  <div class="row">
                    <div class="col-md">
                      <H6 class="text-center">Din joc + Moduri : </H6>
                      <?php
                                    $caleFisier = "json-files/utilaje.json";
                                    $continutFisier = file_get_contents($caleFisier);
                                    $dateUtilaje = json_decode($continutFisier, true);
                                    $countHeadereNormale=$countCornHeader=$countForageHeader=$countPotatoHeader=$countBeetHeader=$countSugarcaneHeader=$countCottonHeader=$countTrailerHeader=0;
                                    $countG=$countF=$countP=$countB=$countS=$countC=$countO=0;
                                    foreach($dateUtilaje['utilaje_joc'] as $utilaj){
                                      if($utilaj['categorie']=="CombinaG"){
                                        $countG++;
                                      }
                                      if($utilaj['categorie']=="CombinaF"){
                                        $countF++;
                                      }
                                      if($utilaj['categorie']=="CombinaP"){
                                        $countP++;
                                      }
                                      if($utilaj['categorie']=="CombinaB"){
                                        $countB++;
                                      }
                                      if($utilaj['categorie']=="CombinaS"){
                                        $countS++;
                                      }
                                      if($utilaj['categorie']=="CombinaC"){
                                        $countC++;
                                      }
                                      if($utilaj['categorie']=="CombinaO"){
                                        $countO++;
                                      }
                                      if($utilaj['categorie']=="Header"){
                                        $countHeadereNormale++;
                                      }
                                      if($utilaj['categorie']=="CornHeader"){
                                        $countCornHeader++;
                                      }
                                      if($utilaj['categorie']=="ForageHeader"){
                                        $countForageHeader++;
                                      }
                                      if($utilaj['categorie']=="PotatoHeader"){
                                        $countPotatoHeader++;
                                      }
                                      if($utilaj['categorie']=="BeetHeader"){
                                        $countBeetHeader++;
                                      }
                                      if($utilaj['categorie']=="SugarcaneHeader"){
                                        $countSugarcaneHeader++;
                                      }
                                      if($utilaj['categorie']=="CottonHeader"){
                                        $countCornHeader++;
                                      }
                                      if($utilaj['categorie']=="TrailerHeader"){
                                        $countTrailerHeader++;
                                      }
                                    }
                                ?>
                      <ul class="list-group">
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=CombinaG'?>">Grane</a>
                          <?php echo '<span class="point">' . $countG . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=CombinaF'?>">Forage</a>
                          <?php echo '<span class="point">' . $countF . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=CombinaP'?>">Potato</a>
                          <?php echo '<span class="point">' . $countP . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=CombinaB'?>">Beet</a>
                          <?php echo '<span class="point">' . $countB . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=CombinaS'?>">Sugarcane</a>
                          <?php echo '<span class="point">' . $countS . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=CombinaC'?>">Cotton</a>
                          <?php echo '<span class="point">' . $countC . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=CombinaO'?>">Olive</a>
                          <?php echo '<span class="point">' . $countO . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=TrailerHeader'?>">Remorci Header</a>
                          <?php echo '<span class="point">' . $countTrailerHeader . '</span>'; ?>
                        </li>
                      </ul>
                    </div>
                    <div class="col-md">
                      <h6 class="text-center">Atasamente</h6>
                      <ul class="list-group">
                      <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Header'?>">Headere normale</a>
                          <?php echo '<span class="point">' . $countHeadereNormale . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=CornHeader'?>">Headere porumb</a>
                          <?php echo '<span class="point">' . $countCornHeader . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=ForageHeader'?>">Headere forage</a>
                          <?php echo '<span class="point">' . $countForageHeader . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=PotatoHeader'?>">Headere cartofi</a>
                          <?php echo '<span class="point">' . $countPotatoHeader . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=BeetHeader'?>">Headere beet</a>
                          <?php echo '<span class="point">' . $countBeetHeader . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=SugarcaneHeader'?>">Headere sugarcane</a>
                          <?php echo '<span class="point">' . $countSugarcaneHeader . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=CottonHeader'?>">Headere cotton</a>
                          <?php echo '<span class="point">' . $countCottonHeader . '</span>'; ?>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <h6>Ori , vezi toate : <a href="<?php echo 'shop_individual.php?choice=ToateCombine'?>"><img src="https://cdn-icons-png.flaticon.com/128/1170/1170678.png" alt=""></a></h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  if (isset($dateUtilaje['branduri']) && is_array($dateUtilaje['branduri'])) {
                    $count_elemente=count($dateUtilaje['branduri']);
                    ?>  
                      <ul class="lista_de_branduri">
                    <?php
                      $index=0;
                      foreach ($dateUtilaje['branduri'] as $brand) {
                          if (isset($brand['pentru'])) {
                              $index++;
                              $categorii = explode(',', $brand['pentru']);
                              if (in_array('Combine', $categorii)) {
                                if($index<$count_elemente){
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].' , </li>';
                                }else{
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].'</li>';
                                }
                              }
                          }
                      }
                  } else {
                      echo "Array-ul sau cheia nu există în fișierul JSON.";
                  }
                ?> 
            </div>
            <div class="submenu" id="submenu-UtilajeSemanat">
                <h6 class="title_submenu">Utilaje si extensii pentru plantat/semanat</h6>
                <div class="container-fluid mx-auto">
                  <div class="row">
                    <div class="col-md-6">
                      <?php
                        $caleFisier = "json-files/utilaje.json";
                        $continutFisier = file_get_contents($caleFisier);
                        $dateUtilaje = json_decode($continutFisier, true);
                        $countSeeder=$countPlanter=$countCultivator=$countSubsoiler=$countPlow=$countDisc=$countPowerHarrow=$countPaleti=0;
                        foreach($dateUtilaje['utilaje_joc'] as $utilaj){
                          if($utilaj['categorie']=='Seeder'){
                            $countSeeder++;
                          }
                          if($utilaj['categorie']=='Planter'){
                            $countPlanter++;
                          }
                          if($utilaj['categorie']=='Cultivator'){
                            $countCultivator++;
                          }
                          if($utilaj['categorie']=='Subsoiler'){
                            $countSubsoiler++;
                          }
                          if($utilaj['categorie']=='Plow'){
                            $countPlow++;
                          }
                          if($utilaj['categorie']=='Disc'){
                            $countDisc++;
                          }
                          if($utilaj['categorie']=='PowerHarrow'){
                           $countPowerHarrow++; 
                          }
                          if($utilaj['categorie']=='Seminte'){
                            $countPaleti++;
                          }
                        }
                      ?>
                      <ul class="list-group">
                          <li class="list-group-item">
                                  <a href="<?php echo 'shop_individual.php?choice=Seeder'?>">Seedere </a>
                                  <?php echo '<span class="point">' . $countSeeder . '</span>'; ?>
                           </li>
                           <li class="list-group-item">
                                  <a href="<?php echo 'shop_individual.php?choice=Planter'?>">Plantere </a>
                                  <?php echo '<span class="point">' . $countPlanter . '</span>'; ?>
                           </li>
                           <li class="list-group-item">
                                  <a href="<?php echo 'shop_individual.php?choice=Cultivator'?>">Cultivatoare </a>
                                  <?php echo '<span class="point">' . $countCultivator . '</span>'; ?>
                           </li>
                           <li class="list-group-item">
                                  <a href="<?php echo 'shop_individual.php?choice=Subsoiler'?>">Subsoilere </a>
                                  <?php echo '<span class="point">' . $countSubsoiler . '</span>'; ?>
                           </li>
                      </ul>
                    </div>
                    <div class="col-md-6">
                    <li class="list-group-item">
                                  <a href="<?php echo 'shop_individual.php?choice=Plow'?>">Pluguri </a>
                                  <?php echo '<span class="point">' . $countPlow . '</span>'; ?>
                           </li>
                           <li class="list-group-item">
                                  <a href="<?php echo 'shop_individual.php?choice=Disc'?>">Discuri </a>
                                  <?php echo '<span class="point">' . $countDisc . '</span>'; ?>
                           </li>
                           <li class="list-group-item">
                                  <a href="<?php echo 'shop_individual.php?choice=PowerHarrow'?>">Power Harrow </a>
                                  <?php echo '<span class="point">' . $countPowerHarrow . '</span>'; ?>
                           </li>
                           <li class="list-group-item">
                                  <a href="<?php echo 'shop_individual.php?choice=Seminte'?>">Seminte </a>
                                  <?php echo '<span class="point">' . $countPaleti . '</span>'; ?>
                           </li>
                    </div>
                  </div>
                </div>
                <h6>Ori , vezi toate : <a href="<?php echo 'shop_individual.php?choice=ToateSemanat'?>"><img src="https://cdn-icons-png.flaticon.com/128/1170/1170678.png" alt=""></a></h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  if (isset($dateUtilaje['branduri']) && is_array($dateUtilaje['branduri'])) {
                    $count_elemente=count($dateUtilaje['branduri']);
                    ?>  
                      <ul class="lista_de_branduri">
                    <?php
                      $index=0;
                      foreach ($dateUtilaje['branduri'] as $brand) {
                          if (isset($brand['pentru'])) {
                              $index++;
                              $categorii = explode(',', $brand['pentru']);
                              if (in_array('PrelucrareSol', $categorii)) {
                                if($index<$count_elemente){
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].' , </li>';
                                }else{
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].'</li>';
                                }
                              }
                          }
                      }
                  } else {
                      echo "Array-ul sau cheia nu există în fișierul JSON.";
                  }
                ?> 
            </div>
            <div class="submenu" id="submenu-UtilajeRecoltat">
                <h6 class="title_submenu">Meniu Utilaje pentru Ierboase</h6>
                <div class="content mt-3">
                  <div class="row">
                    <div class="col-md-6">
                      <ul class="list-group">
                      <?php
                        $caleFisier = "json-files/utilaje.json";
                        $continutFisier = file_get_contents($caleFisier);
                        $dateUtilaje = json_decode($continutFisier, true);
                        $countBalotiera=$countWeeder=$countGrasslandCare=$countMowers=0;
                        $countTeeders=$countWindrower=$countBaleLoaders=$countBaleWrappers=0;
                        foreach($dateUtilaje['utilaje_joc'] as $utilaj){
                          if($utilaj['categorie']=='Balotiera' || $utilaj['categorie']=='BalotieraA'){
                            $countBalotiera++;
                          }
                          if($utilaj['categorie']=='Weeder'){
                            $countWeeder++;
                          }
                          if($utilaj['categorie']=='GrasslandCare'){
                            $countGrasslandCare++;
                          }
                          if($utilaj['categorie']=='Mowers'){
                            $countMowers++;
                          }
                          if($utilaj['categorie']=='Teeder'){
                            $countTeeders++;
                          }
                          if($utilaj['categorie']=='Windrower'){
                            $countWindrower++;
                          }
                          if($utilaj['categorie']=='BaleLoaders'){
                            $countBaleLoaders++;
                          }
                          if($utilaj['categorie']=='BaleWrappers'){
                            $countBaleWrappers++;
                          }
                        }
                      ?>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Balotiera'?>">Balotiere</a>
                          <?php echo '<span class="point">' . $countBalotiera . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Weeder'?>">Weeder</a>
                          <?php echo '<span class="point">' . $countWeeder . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=GrasslandCare'?>">Grassland Care</a>
                          <?php echo '<span class="point">' . $countGrasslandCare . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Mowers'?>">Mowers</a>
                          <?php echo '<span class="point">' . $countMowers . '</span>'; ?>
                        </li>
                      </ul>
                    </div>
                    <div class="col-md-6">
                      <ul class="list-group">
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Teeder'?>">Teeder</a>
                          <?php echo '<span class="point">' . $countTeeders . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Windrower'?>">Windrowers</a>
                          <?php echo '<span class="point">' . $countWindrower . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=BaleLoaders'?>">Bale Loaders</a>
                          <?php echo '<span class="point">' . $countBaleLoaders . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=BaleWrappers'?>">Bale Wrappers</a>
                          <?php echo '<span class="point">' . $countBaleWrappers . '</span>'; ?>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <h6>Ori , vezi toate : <a href="<?php echo 'shop_individual.php?choice=ToateBaler'?>"><img src="https://cdn-icons-png.flaticon.com/128/1170/1170678.png" alt=""></a></h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  if (isset($dateUtilaje['branduri']) && is_array($dateUtilaje['branduri'])) {
                    $count_elemente=count($dateUtilaje['branduri']);
                    ?>  
                      <ul class="lista_de_branduri">
                    <?php
                      $index=0;
                      foreach ($dateUtilaje['branduri'] as $brand) {
                          if (isset($brand['pentru'])) {
                              $index++;
                              $categorii = explode(',', $brand['pentru']);
                              if (in_array('Baler', $categorii)) {
                                if($index<$count_elemente){
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].' , </li>';
                                }else{
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].'</li>';
                                }
                              }
                          }
                      }
                  } else {
                      echo "Array-ul sau cheia nu există în fișierul JSON.";
                  }
                ?> 
            </div>
            <div class="submenu" id="submenu-UtilajeFertilizat">
                <h6 class="title_submenu">Meniu Utilaje pentru Fertilizat</h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  $countUtilajeFertilizat=$countPaletiFertilizant=$countPaletiIerbicid=0;
                  $countUtilajeIerbicid=0;
                  $countUtilajeFertilizatMari=0;
                  foreach($dateUtilaje['utilaje_joc'] as $utilaj){
                    if($utilaj['categorie']=='CropProtection'){
                      $countUtilajeIerbicid++;
                    }
                    if($utilaj['categorie']=='SpreaderFertilizant'){
                      $countUtilajeFertilizat++;
                    }
                    if($utilaj['categorie']=='UtilajeFertilizatMari'){
                      $countUtilajeFertilizatMari++;
                    }
                    if($utilaj['categorie']=='Fertilizant'){
                      $countPaletiFertilizant++;
                    }
                    if($utilaj['categorie']=='Ierbicid'){
                      $countPaletiIerbicid++;
                    }
                  }
                ?>
                <ul class="list-group mx-auto w-50">
                  <li class="list-group-item">
                    <a href="<?php echo 'shop_individual.php?choice=CropProtection'?>">Ierbicidare </a>
                    <?php echo '<span class="point">' . $countUtilajeIerbicid . '</span>'; ?>
                  </li>
                  <li class="list-group-item">
                    <a href="<?php echo 'shop_individual.php?choice=SpreaderFertilizant'?>">Fertilizare </a>
                    <?php echo '<span class="point">' . $countUtilajeFertilizat . '</span>'; ?>
                </li>
                  <li class="list-group-item">
                    <a href="<?php echo 'shop_individual.php?choice=Fertilizant'?>">Fertilizant </a>
                    <?php echo '<span class="point">' . $countPaletiFertilizant . '</span>'; ?>
                  </li>
                  <li class="list-group-item">
                    <a href="<?php echo 'shop_individual.php?choice=Ierbicid'?>">Ierbicid </a>
                    <?php echo '<span class="point">' . $countPaletiIerbicid . '</span>'; ?>
                  </li>
                </ul>
                <h6>Ori , vezi toate : <a href="<?php echo 'shop_individual.php?choice=ToateUtilajeFertilizat'?>"><img src="https://cdn-icons-png.flaticon.com/128/1170/1170678.png" alt=""></a></h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  if (isset($dateUtilaje['branduri']) && is_array($dateUtilaje['branduri'])) {
                    $count_elemente=count($dateUtilaje['branduri']);
                    ?>  
                      <ul class="lista_de_branduri">
                    <?php
                      $index=0;
                      foreach ($dateUtilaje['branduri'] as $brand) {
                          if (isset($brand['pentru'])) {
                              $index++;
                              $categorii = explode(',', $brand['pentru']);
                              if (in_array('UtilajeFertilizat', $categorii)) {
                                if($index<$count_elemente){
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].' , </li>';
                                }else{
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].'</li>';
                                }
                              }
                          }
                      }
                  } else {
                      echo "Array-ul sau cheia nu există în fișierul JSON.";
                  }
                ?> 
              </div>
            <div class="submenu" id="submenu-EchipamenteAnimale">
                <h6 class="title_submenu">Meniu Echipamente pentru Animale</h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  $countNutritie=$countTransport=$countAnimale=$countUtilaje=$countConstructii=$countSlurryTanks=0;
                  foreach($dateUtilaje['utilaje_joc'] as $utilaj){
                    if($utilaj['categorie']=="Animals"){
                      $countNutritie++;
                    }
                    if($utilaj['categorie']=="AnimalsTransport"){
                      $countTransport++;
                    }
                    if($utilaj['categorie']=="BuyAnimal"){
                      $countAnimale++;
                    }
                    if($utilaj['categorie']=="AnimalsMachinery"){
                      $countUtilaje++;
                    }
                    if($utilaj['categorie']=="AnimalsBuilds"){
                      $countConstructii++;
                    }
                    if($utilaj['categorie']=="SlurryTanks"){
                      $countSlurryTanks++;
                    }
                  }
                ?>
                <div class="content">
                  <div class="row mx-auto w-50">
                    <div class="col-md">
                      <ul class="list-group">
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Animals'?>">Echipament nutritie</a>
                          <?php echo '<span class="point">' . $countNutritie . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=AnimalsTransport'?>">Transport animale</a>
                          <?php echo '<span class="point">' . $countTransport . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=BuyAnimal'?>">Animale vii</a>
                          <?php echo '<span class="point">' . $countAnimale . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=AnimalsMachinery'?>">Utilaje animale</a>
                          <?php echo '<span class="point">' . $countUtilaje . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=AnimalsBuilds'?>">Constructii animale</a>
                          <?php echo '<span class="point">' . $countConstructii . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=SlurryTanks'?>">Slurry tanks</a>
                          <?php echo '<span class="point">' . $countSlurryTanks . '</span>'; ?>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <h6>Ori , vezi toate : <a href="<?php echo 'shop_individual.php?choice=ToateAnimale'?>"><img src="https://cdn-icons-png.flaticon.com/128/1170/1170678.png" alt=""></a></h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  if (isset($dateUtilaje['branduri']) && is_array($dateUtilaje['branduri'])) {
                    $count_elemente=count($dateUtilaje['branduri']);
                    ?>  
                      <ul class="lista_de_branduri">
                    <?php
                      $index=0;
                      foreach ($dateUtilaje['branduri'] as $brand) {
                          if (isset($brand['pentru'])) {
                              $index++;
                              $categorii = explode(',', $brand['pentru']);
                              if (in_array('Animale', $categorii)) {
                                if($index<$count_elemente){
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].' , </li>';
                                }else{
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].'</li>';
                                }
                              }
                          }
                      }
                  } else {
                      echo "Array-ul sau cheia nu există în fișierul JSON.";
                  }
                ?> 
            </div>
            
            <div class="submenu" id="submenu-EchipamenteForestiere">
                <h6 class="title_submenu">Meniu Echipamente Forestiere</h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  $countCutting=$countTransportF=$countChop=$countTaf=$countArm=$countOther=0;
                  foreach($dateUtilaje['utilaje_joc'] as $utilaj){
                    if($utilaj['categorie']=="ForestryCutting"){
                      $countCutting++;
                    }
                    if($utilaj['categorie']=="ForestryTransporting"){
                      $countTransportF++;
                    }
                    if($utilaj['categorie']=="ForestryChop"){
                      $countChop++;
                    }
                    if($utilaj['categorie']=="ForestryTaf"){
                      $countTaf++;
                    }
                    if($utilaj['categorie']=="ForestryArm"){
                      $countArm++;
                    }
                    if($utilaj['categorie']=="ForestryOther"){
                      $countOther++;
                    }
                  }
                ?>
                <div class="content">
                  <div class="row mx-auto w-50">
                    <div class="col">
                      <ul class="list-group">
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=ForestryCutting'?>">Echipamente taiere</a>
                          <?php echo '<span class="point">' . $countCutting . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=ForestryTransporting'?>">Echipamente transport</a>
                          <?php echo '<span class="point">' . $countTransportF . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=ForestryChop'?>">Echipamente rumegus</a>
                          <?php echo '<span class="point">' . $countChop . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=ForestryTaf'?>">Echipament tras</a>
                          <?php echo '<span class="point">' . $countTaf . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=ForestryArm'?>">Echipamente cu brate</a>
                          <?php echo '<span class="point">' . $countArm . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=ForestryOther'?>">Alte atasamente si utilaje</a>
                          <?php echo '<span class="point">' . $countOther . '</span>'; ?>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <h6>Ori , vezi toate : <a href="<?php echo 'shop_individual.php?choice=ToateForestry'?>"><img src="https://cdn-icons-png.flaticon.com/128/1170/1170678.png" alt=""></a></h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  if (isset($dateUtilaje['branduri']) && is_array($dateUtilaje['branduri'])) {
                    $count_elemente=count($dateUtilaje['branduri']);
                    ?>  
                      <ul class="lista_de_branduri">
                    <?php
                      $index=0;
                      foreach ($dateUtilaje['branduri'] as $brand) {
                          if (isset($brand['pentru'])) {
                              $index++;
                              $categorii = explode(',', $brand['pentru']);
                              if (in_array('Forestry', $categorii)) {
                                if($index<$count_elemente){
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].' , </li>';
                                }else{
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].'</li>';
                                }
                              }
                          }
                      }
                  } else {
                      echo "Array-ul sau cheia nu există în fișierul JSON.";
                  }
                ?> 
            </div>
            <div class="submenu" id="submenu-EchipamenteManipulare">
                <h6 class="title_submenu">Meniu Echipamente de Manipulare</h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  $countFront=$countWheel=$countTelehander=$countSkid=$countForklift=0;
                  $countFrontA=$countWheelA=$countTelehanderA=$countSkidA=$countForkliftA=0;
                  foreach($dateUtilaje['utilaje_joc'] as $utilaj){
                    if($utilaj['categorie']=="FrontLoaders"){
                      $countFront++;
                    }
                    if($utilaj['categorie']=="WheelLoaders"){
                      $countWheel++;
                    }
                    if($utilaj['categorie']=="Telehanders"){
                      $countTelehander++;
                    }
                    if($utilaj['categorie']=="Skidsteer"){
                      $countSkid++;
                    }
                    if($utilaj['categorie']=="Forklifts"){
                      $countForklift++;
                    }
                    if($utilaj['categorie']=="FrontLoadersA"){
                      $countFrontA++;
                    }
                    if($utilaj['categorie']=="WheelLoadersA"){
                      $countWheelA++;
                    }
                    if($utilaj['categorie']=="TelehandersA"){
                      $countTelehanderA++;
                    }
                    if($utilaj['categorie']=="SkidsteerA"){
                      $countSkidA++;
                    }
                  }
                ?>
                <div class="content">
                  <div class="row mx-auto w-75">
                    <div class="col-md-6">
                      <h6 class="text-center">Manipulatoare : </h6>
                      <ul class="list-group">
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=FrontLoaders'?>">Front Loaders</a>
                          <?php echo '<span class="point">' . $countFront . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=WheelLoaders'?>">Wheel Loaders</a>
                          <?php echo '<span class="point">' . $countWheel . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Telehanders'?>">Telehanders</a>
                          <?php echo '<span class="point">' . $countTelehander . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Skidsteer'?>">Skidsteers Loaders</a>
                          <?php echo '<span class="point">' . $countSkid . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Forklifts'?>">Forklifts</a>
                          <?php echo '<span class="point">' . $countForklift . '</span>'; ?>
                        </li>
                      </ul>
                    </div>
                    <div class="col-md-6">
                      <h6 class="text-center">Atasamente : </h6>
                      <ul class="list-group">
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=FrontLoadersA'?>">Front Loaders Atasamente</a>
                          <?php echo '<span class="point">' . $countFrontA . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=WheelLoadersA'?>">Wheel Loaders Atasamente</a>
                          <?php echo '<span class="point">' . $countWheelA . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=TelehandersA'?>">Telehanders Atasamente</a>
                          <?php echo '<span class="point">' . $countTelehanderA . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=SkidsteerA'?>">Skidsteers Loaders Atasamente</a>
                          <?php echo '<span class="point">' . $countSkidA . '</span>'; ?>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <h6>Ori , vezi toate : <a href="<?php echo 'shop_individual.php?choice=ToateManipulatoare'?>"><img src="https://cdn-icons-png.flaticon.com/128/1170/1170678.png" alt=""></a></h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  if (isset($dateUtilaje['branduri']) && is_array($dateUtilaje['branduri'])) {
                    $count_elemente=count($dateUtilaje['branduri']);
                    ?>  
                      <ul class="lista_de_branduri">
                    <?php
                      $index=0;
                      foreach ($dateUtilaje['branduri'] as $brand) {
                          if (isset($brand['pentru'])) {
                              $index++;
                              $categorii = explode(',', $brand['pentru']);
                              if (in_array('Manipulatoare', $categorii)) {
                                if($index<$count_elemente){
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].' , </li>';
                                }else{
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].'</li>';
                                }
                              }
                          }
                      }
                  } else {
                      echo "Array-ul sau cheia nu există în fișierul JSON.";
                  }
                ?> 
            </div>
            <div class="submenu" id="submenu-Remorci">
                <h6 class="title_submenu">Meniu Remorci</h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  $countRemorci=$countAuger=$countForage=$countLowloaders=0;
                  foreach($dateUtilaje['utilaje_joc'] as $utilaj){
                    if($utilaj['categorie']=="Trailers"){
                      $countRemorci++;
                    }
                    if($utilaj['categorie']=="AugerWagons"){
                      $countAuger++;
                    }
                    if($utilaj['categorie']=="ForageWagons"){
                      $countForage++;
                    }
                    if($utilaj['categorie']=="LowLoaders"){
                      $countLowloaders++;
                    }
                  }
                ?>
                <div class="content">
                  <div class="row mx-auto w-50">
                    <div class="col-md">
                      <ul class="list-group">
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Trailers'?>">Remorci normale</a>
                          <?php echo '<span class="point">' . $countRemorci . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=AugerWagons'?>">Auger Wagons</a>
                          <?php echo '<span class="point">' . $countAuger . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=ForageWagons'?>">Forage Wagons</a>
                          <?php echo '<span class="point">' . $countForage . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=LowLoaders'?>">Low Loaders</a>
                          <?php echo '<span class="point">' . $countLowloaders . '</span>'; ?>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <h6>Ori , vezi toate : <a href="<?php echo 'shop_individual.php?choice=ToateRemorci'?>"><img src="https://cdn-icons-png.flaticon.com/128/1170/1170678.png" alt=""></a></h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  if (isset($dateUtilaje['branduri']) && is_array($dateUtilaje['branduri'])) {
                    $count_elemente=count($dateUtilaje['branduri']);
                    ?>  
                      <ul class="lista_de_branduri">
                    <?php
                      $index=0;
                      foreach ($dateUtilaje['branduri'] as $brand) {
                          if (isset($brand['pentru'])) {
                              $index++;
                              $categorii = explode(',', $brand['pentru']);
                              if (in_array('Remorci', $categorii)) {
                                if($index<$count_elemente){
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].' , </li>';
                                }else{
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].'</li>';
                                }
                              }
                          }
                      }
                  } else {
                      echo "Array-ul sau cheia nu există în fișierul JSON.";
                  }
                  ?>
            </div>
            <div class="submenu" id="submenu-Masini">
                <h6 class="title_submenu">Meniu Masini</h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  $countCatB=$countCatC=$countGabaritic=$countAutobuz=0;
                  foreach($dateUtilaje['utilaje_joc'] as $utilaj){
                    if($utilaj['categorie']=="CatB"){
                      $countCatB++;
                    }
                    if($utilaj['categorie']=="CatC"){
                      $countCatC++;
                    }
                    if($utilaj['categorie']=="Gabaritic"){
                      $countGabaritic++;
                    }
                    if($utilaj['categorie']=="Autobuz"){
                      $countAutobuz++;
                    }
                  }
                ?>
                <div class="content">
                  <div class="row mx-auto w-50">
                    <div class="col-md">
                      <ul class="list-group">
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=CatB'?>">Autovehicule Categoria B</a>
                          <?php echo '<span class="point">' . $countCatB . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=CatC'?>">Autovehicule Categoria C</a>
                          <?php echo '<span class="point">' . $countCatC . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Gabaritic'?>">Gabaritice</a>
                          <?php echo '<span class="point">' . $countGabaritic . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Autobuz'?>">Autobuze & Microbuze</a>
                          <?php echo '<span class="point">' . $countAutobuz . '</span>'; ?>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <h6>Ori , vezi toate : <a href="<?php echo 'shop_individual.php?choice=ToateMasini'?>"><img src="https://cdn-icons-png.flaticon.com/128/1170/1170678.png" alt=""></a></h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  if (isset($dateUtilaje['branduri']) && is_array($dateUtilaje['branduri'])) {
                    $count_elemente=count($dateUtilaje['branduri']);
                    ?>  
                      <ul class="lista_de_branduri">
                    <?php
                      $index=0;
                      foreach ($dateUtilaje['branduri'] as $brand) {
                          if (isset($brand['pentru'])) {
                              $index++;
                              $categorii = explode(',', $brand['pentru']);
                              if (in_array('Masini', $categorii)) {
                                if($index<$count_elemente){
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].' , </li>';
                                }else{
                                  echo '<li class="element_lista_de_branduri">'.$brand['denumire'].'</li>';
                                }
                              }
                          }
                      }
                  } else {
                      echo "Array-ul sau cheia nu există în fișierul JSON.";
                  }
                  ?>
            </div>
            <div class="submenu" id="submenu-Altele">
                <h6 class="title_submenu">Meniu Moduri</h6>
                <?php
                  $caleFisier = "json-files/utilaje.json";
                  $continutFisier = file_get_contents($caleFisier);
                  $dateUtilaje = json_decode($continutFisier, true);
                  $countMods=$countMisc=$countCladiri=0;
                  foreach($dateUtilaje['utilaje_joc'] as $utilaj){
                    if($utilaj['categorie']=="Mods"){
                      $countMods++;
                    }
                    if($utilaj['categorie']=="Misc")
                    $countMisc++;
                  if($utilaj['categorie']=="Cladire"){
                      $countCladiri++;
                  }
                  }
                ?>
                <ul class="list-group">
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Mods'?>">Moduri</a>
                          <?php echo '<span class="point">' . $countMods . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Misc'; ?>">Utilitati</a>
                          <?php echo '<span class="point">' . $countMisc . '</span>'; ?>
                        </li>
                        <li class="list-group-item">
                          <a href="<?php echo 'shop_individual.php?choice=Cladire'; ?>">Cladiri</a>
                          <?php echo '<span class="point">' . $countCladiri . '</span>'; ?>
                        </li>
                </ul>
            </div>
        </div>
        <div class="col col-2" id="col2-content">
          <div class="continut-col2">
          <?php
            date_default_timezone_set('Europe/Bucharest'); // Setează fusul orar pentru România
            $ora_curenta = date("H");
            if ($ora_curenta >= 0 && $ora_curenta < 13) {
                echo ' Buna dimineata';
            } elseif ($ora_curenta >= 13 && $ora_curenta < 18) {
                echo ' Buna ziua';
            } else {
                echo ' Buna seara';
            }
            if (isset($_SESSION['username'])) {
              echo " ".$_SESSION['username'];
            } else {
                echo ' Nu sunteti conectat . Va rugam sa va conectati pentru a avea acces la toate utilitatile! ';
            }
          ?>
          </div>
        </div>
</div>
<div class="container text-center">
  <h4 class="mt-3 text-center titlu-fs22">Farming Simulator 2022 - Nu ezita să achiziționezi jocul pe una dintre platformele dorite.</h4>
  <figcaption class="text-center">
    Cumpăra jocul și te poți distra cu noile DLC-uri <cite title="Butoanele de jos">Vezi linkurile</cite>
    <img src="https://cdn-icons-png.flaticon.com/128/13039/13039449.png?ga=GA1.1.1249271346.1703972784&semt=ais" class="imagine-gradient" alt="">
  </figcaption>
  <button class="btn-hover color-6" onclick="window.open('https://store.steampowered.com/app/1248130/Farming_Simulator_22/', '_blank')">
      Steam
  </button>

  <button class="btn-hover color-6" onclick="window.open('https://www.g2a.com/farming-simulator-22-pc-steam-key-europe-i10000256874003?uuid=fd21539f-36ea-4d67-a781-6608f7022c0e&er=4857543a99e4ed489eb91804072b14a7033c0d32a322273edc953d36bc9c4a754905319d6a760e3bbb06378a039b9eb4&___language=en&adid=GA-RO_PB_MIX_PMAX_FEED_All-Products&id=47&gad_source=1&gclid=CjwKCAiAnL-sBhBnEiwAJRGigue4Ik3dFQe4AcQBg8fhW8S1wLjgO3_7Npi_o_98NUYaIbqCokOVxBoCAT0QAvD_BwE&gclsrc=aw.ds', '_blank')">
      G2A
  </button>

  <button class="btn-hover color-6" onclick="window.open('https://store.epicgames.com/en-US/p/farming-simulator-22', '_blank')">
      EpicGames
  </button>
</div>
<?php

// Citirea conținutului fișierului JSON
$json_data = file_get_contents('json-files/utilaje.json');

// Decodificarea datelor JSON într-un array asociativ
$data = json_decode($json_data, true);

// Funcție pentru a extrage puterea din descriere
function extrage_putere($descriere) {
    // Caută potrivirea pentru puterea în descriere
    preg_match('/(\d+)\s*HP/i', $descriere, $matches);
    // Verifică dacă a fost găsită o potrivire și returnează puterea sau 0 în caz contrar
    return isset($matches[1]) ? intval($matches[1]) : 0;
}

// Funcție pentru a sorta utilajele în funcție de puterea lor
function comparare_putere($a, $b) {
    // Extrage puterea pentru fiecare utilaj
    $putere_a = extrage_putere($a['descriere']);
    $putere_b = extrage_putere($b['descriere']);
    // Compară puterile
    return $putere_b - $putere_a;
}

// Sortează utilajele în funcție de puterea lor folosind funcția de sortare definită
usort($data['utilaje_joc'], 'comparare_putere');

// Afisează primele 10 cele mai puternice utilaje
$top_10_puternice = array_slice($data['utilaje_joc'], 0, 10);
?>
<div class="cover">
  <h4><img src="https://cdn-icons-png.flaticon.com/128/4473/4473731.png" alt="" class="iconita-heading">Top cele mai <span class="titlu-criteriu">puternice</span> 10 utilaje</h4>
  <button class="left search-header-button" onclick="leftScroll()">
    <i class="fa fa-angle-double-left"></i>
  </button>
  <div class="scroll-images">
    <?php
      $index=0;
      foreach($top_10_puternice as $element){
        $index++;
        ?>
          <div class="child">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-<?php echo $index; ?>-circle-fill imagine-bila" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0ZM6.646 6.24c0-.691.493-1.306 1.336-1.306.756 0 1.313.492 1.313 1.236 0 .697-.469 1.23-.902 1.705l-2.971 3.293V12h5.344v-1.107H7.268v-.077l1.974-2.22.096-.107c.688-.763 1.287-1.428 1.287-2.43 0-1.266-1.031-2.215-2.613-2.215-1.758 0-2.637 1.19-2.637 2.402v.065h1.271v-.07Z"/>
            </svg>
            <img src="<?php check_index_for_img_path($index) ?>" alt="<?php echo $index; ?>" class="iconita-heading">
            <div class="vertical-bar"></div>
            <?php
                if($element['categorie']=='SmallTractors' || $element['categorie']=='MediumTractors' || $element['categorie']=='LargeTractors'){
                    $link_pentru_top='media/shop/tractors'.$element["cod_produs"];
                }
                if($element['categorie']!='SmallTractors' || $element['categorie']!='MediumTractors' || $element['categorie']!='LargeTractors'){
                    $img_path = strtolower($element['brand'].'-'.$element['denumire']);
                    $link_pentru_top='media/shop/atasamente/'.$img_path;
                }
            ?>
            <div class="continut-informatii">
              <h4 class="mt-3"><?php echo $element['denumire']; ?></h4>
              <p><?php echo $element['cod_produs']; ?></p>
              <p><span class="label-imprumut">Categorie : </span><?php echo $element['categorie']; ?></p>
              <p>Putere: <?php echo extragePuterePHP($element['descriere']); ?> HP</p>
            </div>
            <img src="<?php echo $link_pentru_top. '.png'; ?>" alt="Imagine pentru <?php echo $element["denumire"]; ?>" class="imagine-bila">
        </div>

        <?php
      }
    ?>
  </div>
  <button class="right search-header-button" onclick="rightScroll()">
    <i class="fa fa-angle-double-right"></i>
  </button>
</div>



<h4 class="mx-auto text-center w-50 mt-5 text-light gradient-background">Listă completă cu toate culturile si produsele din Farming Simulator 2022</h4>
    <div class="grid-container">
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/2316/2316448.png" alt="Icon 1">
          <p>Barley</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/12924/12924529.png" alt="Icon 2">
          <p>Canola</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/9432/9432700.png" alt="Icon 2">
          <p>Corn</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/519/519046.png" alt="Icon 2">
          <p>Cotton</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/8645/8645285.png" alt="Icon 2">
          <p>Grapes</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/704/704834.png" alt="Icon 2">
          <p>Grass</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/11905/11905339.png" alt="Icon 2">
          <p>Oat</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/2913/2913520.png" alt="Icon 2">
          <p>Poplar</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/540/540254.png" alt="Icon 5">
          <p>OilSeed Radish</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/3130/3130149.png" alt="Icon 6">
          <p>Olives</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/1652/1652127.png" alt="Icon 6">
          <p>Potato</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/1195/1195161.png?ga=GA1.1.1249271346.1703972784&semt=ais" alt="Icon 6">
          <p>Sorghum</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/8945/8945302.png" alt="Icon 6">
          <p>Soybeans</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/3267/3267239.png?ga=GA1.1.1249271346.1703972784&semt=ais" alt="Icon 6">
          <p>Sugarbeet</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/4139/4139049.png?ga=GA1.1.1249271346.1703972784&semt=ais" alt="Icon 6">
          <p>Sugarcane</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/2203/2203403.png?ga=GA1.1.1249271346.1703972784&semt=ais" alt="Icon 6">
          <p>Sunflowers</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/575/575435.png?ga=GA1.1.1249271346.1703972784&semt=ais" alt="Icon 6">
          <p>Wheat</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/489/489969.png?ga=GA1.1.1249271346.1703972784&semt=ais" alt="Icon 6">
          <p>Tree</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/2937/2937736.png" alt="Icon 6">
          <p>Sawdust</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/869/869655.png" alt="Icon 6">
          <p>Milk</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/2713/2713474.png" alt="Icon 6">
          <p>Eggs</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/1145/1145563.png" alt="Icon 6">
          <p>Wool</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/4515/4515613.png" alt="Icon 6">
          <p>Diesel</p>
      </div>
      <div class="grid-item">
          <img src="https://cdn-icons-png.flaticon.com/128/4129/4129528.png" alt="Icon 6">
          <p>Factory products</p>
      </div>
    </div>
<div class="w3-col l6 m6 w3-padding-large">

  <h4>Pentru cumparaturi</h4>
  <p class="w3-text-grey">Aveti sectiunea din antetul paginii principale unde puteti selecta o categorie si cumpara utilaje</p><br>

  <h4>Banca Nationala</h4>
  <p class="w3-text-grey">La sectiunea unitate bancara , mergeti si puteti transfera bani sau face imprumuturi</p><br>

  <h4>Registru Auto</h4>
  <p class="w3-text-grey">Pentru vizualizarea inventarului auto sau vinderea de utilaje personale , puteti merge la Registru Auto</p><br>

  <h4>Primaria Farming Simulator</h4>
  <p class="w3-text-grey">La primarie puteti vizualiza ce detine primaria , amenzile si joburile disponibile</p>    

</div>

<div class="w3-col l6 m6 w3-padding-large">
  <img src="https://cdn-icons-png.flaticon.com/128/16871/16871145.png" alt="Menu" style="width: 50%;">
</div>

</div>
<?php include_once 'footer.php'; ?>
