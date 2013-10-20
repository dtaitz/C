<table align="center">
 
    <thead>
        <tr>
            <th>Your Favorite Items Are...</th>
        </tr>
    </thead>
    <tbody>
    <?php      
         
        foreach ($favorites as $favorite) 
        {
               print("<tr>");
               print("<td>" . $favorite["name"] . "</td>"); 
               print("</tr>");
        }
      
    ?> 
    </tbody>
 </table>
  
<div>
    <a href="index.php">Home</a>
    <a href="logout.php">Log Out</a>
</div>
