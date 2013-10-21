<?php

    // configuration
    require("../includes/config.php"); 
    
          // open csv file
       $handle = fopen("http://food.cs50.net/api/1.3/menus?output=csv", "r");
       if ($handle === false)
       {
           return false;
       }
    
       // determine fields' indices
       $row = fgetcsv($handle);
       for ($i = 0, $n = count($row); $i < $n; $i++)
           $fields[$row[$i]] = $i;
           
       $menus=[];
       
 
       // iterate over CSV file's rows
       while ($row = fgetcsv($handle))
       {
          // extract fields
          $date = $row[$fields["date"]];
          $meal = $row[$fields["meal"]];
          $category = $row[$fields["category"]];
          $recipe = $row[$fields["recipe"]];
          $name = $row[$fields["name"]];
 
         // return an associative array 
         $menus[]= 
         [
            "date" => $date,
            "meal" => $meal,
            "category" => $category,
            "recipe" => $recipe,
            "name" => $name,
         ];
      }
 
      // close CSV file
      fclose($handle);
    
    $breakfasts= [];
    $lunchs=[];
    $dinners=[];
    foreach($menus as $menu)
    {
    
        if($menu["meal"]=== "BREAKFAST")
        {
            $breakfasts[]=["category" => $menu["category"], "recipe" => $menu["recipe"], 
                "name" => $menu["name"]];
        }
        
        if($menu["meal"] === "LUNCH")
        {
            $lunchs[]=["category" => $menu["category"], "recipe" => $menu["recipe"], 
                "name" => $menu["name"]];
        }
        
        if($menu["meal"]=== "DINNER")
        {
            $dinners[]=["category" => $menu["category"], "recipe" => $menu["recipe"], 
                "name" => $menu["name"]];
        }
        
    }
    
    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
       foreach($_POST as $rating)
       {  
          if($rating != "+")
          {
          	  $rated = explode( "^" , $rating);
          	  
          	  
		      if($rated[3] != 'favorite')
		      {
		      
		       $score = query("SELECT rating FROM history WHERE name = ? 
                    AND recipe = ?", $rated[0], $rated[1]);
            
               if ($score === false)
               { 
               
                    $result = query("INSERT INTO history (name, recipe, rating, submissions) VALUES(?, ?, ?, ?)",
                        $rated[0], $rated[1], $rated[3], "1");
               }
               else
               {
                 
                    $query = query("UPDATE history SET submissions = submissions + 1
                        WHERE recipe = ? AND name = ?", $rated[1] , $rated[0]); 

                    $query2 = query("UPDATE history SET rating = rating + ? 
                        WHERE recipe = ? AND name = ?", $rated[3], $rated[1] , $rated[0]); 
                 
                    if ($query === false)
                    {
                      apologize("Error");      
                    }
               }
             }
             else
             {
               $favorite = query("INSERT INTO favorites (user_id, recipe, category, name) VALUES (?,?,?,?)", $_SESSION ["id"],
                    $rated[1], $rated[2], $rated[0] );
             
               $score = EXISTS("SELECT rating FROM history WHERE name = ? 
                    AND recipe = ?", $rated[0], $rated[1]);
            
               if ($score == false)
               {
                    $result = query("INSERT INTO history (name, recipe, rating, submissions) VALUES(?, ?, ?, ?)",
                        $rated[0], $rated[1], "5", "1");
               }
               else
               {
                 
                    $query = query("UPDATE history SET submissions = submissions + 1 AND rating = rating + ?, 
                        WHERE recipe = ? AND name = ?", "5", $rated[1] , $rated[0]); 
                 
                    if ($query === false)
                    {
                      apologize("Error");      
                    }
               }
             }   
                
          
       	  }
     
       }
     }   
    
    // render portfolio
    render("menu_form.php", ["breakfasts" => $breakfasts, "lunchs" => $lunchs, "dinners"=> $dinners]);

?>
