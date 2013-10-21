<?php
    
    $favorites = query("SELECT * FROM favorites WHERE id = ?", $_SESSION["id"]);

    if ($favorites === false)
    {
       apologize("The table cannot be accessed");
    }

    // redirect to template 
    render("favorites_form.php", ["favorites" => $favorites]); 
?>
