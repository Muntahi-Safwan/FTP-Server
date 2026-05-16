<?php
    session_start();
    require('catcheck.php');
    
    echo $choice=sqlChoice();


    function sqlChoice(){
         $choice=0;//no filter
        if($_SESSION['filter_search']==0){
            $choice=0;
        }
        else if(isParentIdNull($_SESSION['filter_search'])){
            $choice=1;//main category
        }
        else{
            $choice=2;//sub category
        }
        return $choice;
    }

    
    


 ?>