<!-- Add new record in the database---->


<html>
    <head>
        <title>Create new record</title>
   
    </head>
<body>
<?php

if($_POST['action']=='create'){
    //include database configuration
    include 'db.php';

    //sql insert statement
    $sql="insert into search_reply ( keyword, reply_msg, created_by, created_time, modified_time )
            values ('{$_POST['keyword']}','{$_POST['reply_msg']}','gaboli',NOW(),NOW())";

    //insert query to the database
    if(mysql_query($sql)){
        //if successful query
        echo "New record was saved.";
    }else{
        //if query failed
        die($sql.">>".mysql_error());
    }
}
?>

<!--we have our html form here where user information will be entered-->
<form action='#' method='post' border='0'>
    <table>
        <tr>
            <td>keyword</td>
            <td><input type='text' name='keyword' /></td>
        </tr>
        <tr>
            <td>reply_msg</td>
            <td><input type='text' name='reply_msg' /></td>
        </tr>
            <td></td>
            <td>
                <input type='hidden' name='action' value='create' />
                <input type='submit' value='Save' />
            </td>
        </tr>
    </table>
</form>
<?php
    echo "<a href='index.php'>Back To List</a>";
?>
</body>
</html>
