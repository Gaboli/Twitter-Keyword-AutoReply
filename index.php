<!-----Index page----->

<html>
    <head>
        <title>tweet</title>
       <link rel="stylesheet" type="text/css" href="http://localhost/dbtwet/index.css" />
    </head>
<body  style=" background-color: graytext">
  <h3 style="text-align: center; margin-top:30px;"> Keywords and Reply Messages </h3> 
<?php
//include database configuration
include 'db.php';

//check if an action was set, we use GET this time since we get the action data from the url
isset($_GET['action']) ? $action=$_GET['action'] : $action="";
   
if($action=='delete'){ //if the user clicked ok, run our delete query
   
    $sql = "DELETE FROM search_reply WHERE id = {$_GET['id']}";
    if(mysql_query($sql)){
        //this will be displayed when the query was successful
        echo "<div>Record was deleted.</div>";
    }else{
        die("SQL: ".$sql." >> ".mysql_error());
    }
}
     
//selecting records
$sql="select * from search_reply";

//query the database
$rs=mysql_query($sql) or die($sql.">>".mysql_error());

//count how many records found
$num=mysql_num_rows($rs);

if($num>0){ //check if more than 0 record found

   ?><table border='1' style="margin-top: 50px; margin-left: 130px; background-color:ActiveCaption;"><!--//start table
 
        //creating our table heading -->
      <tr style="background-color: grey; color: black;">
            <th>Id</th>
            <th>Keyword</th>
            <th>Reply_msg</th>
            <th>Edit</th> <!--//we're gonna add this column for delete action -->
            <th>Delete</th> <!-- //we're gonna add this column for edit action -->
        </tr>
<?php 
        //retrieve our table contents
        while($row=mysql_fetch_array($rs)){
            //extract row
            //this will make $row['firstname'] to
            //just $firstname only
            extract($row);
         
            //creating new table row per record
            ?> <tr>
                <td>  <?php echo $id ?> </td>
                <td><?php echo $keyword ?> </td>
               <td> <?php echo $reply_msg ?> </td>
               <!-- //we will have the edit link here -->
               <td>
                    <a href='edit.php?id= <?php echo $id ?>  '> <img src="/images/edit.png" alt="edit row" style="width: 40px;"/></a>
                </td>
               <!-- //we will have the delete link here, you can also put your edit link here, but for this tutorial we will just include the delete link -->
               <td>
                   <a href='#' onclick='delete_user( <?php echo $id ?>  );'><img src="/images/delete.png" alt="delete row" style="width: 40px;"/></a>
                </td>
		
            </tr><?php 
        } ?>
</table> <!-- END table --><a href='add.php' style="margin-top:10px; margin-left: 132px;text-decoration: none;">Add</a>
 <?php
}else{ //if no records found
    echo "No records found."; ?><a href='add.php' style="margin-top:10px; margin-left: 132px;text-decoration: none;">Add</a>
<?php }
?>

<script type='text/javascript'>
   
    function delete_user( id ){
        //this script helps us to
       
        var answer = confirm('Are you sure?');
        if ( answer ){ //if user clicked ok
            //redirect to url with action as delete and id to the record to be deleted
            window.location = 'index.php?action=delete&id=' + id;
        }
    }
</script>
   
</body>
</html>
