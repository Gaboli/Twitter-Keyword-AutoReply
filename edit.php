 <?php
// Edit records in database


    include 'db.php';
   
    //check if an action was set
    isset($_POST['action']) ? $action=$_POST['action'] : $action="";
   
    if($action=="edit"){
        //update the record if the form was submitted
        $sql="update search_reply
                set
                    keyword='{$_POST['keyword']}',
                    reply_msg='{$_POST['reply_msg']}'
                where
                    id={$_POST['id']}";
        if(mysql_query($sql)){
            //this will be displayed when the query was successful
            echo "<div>Record successfully edited.</div>";
        }else{
            die("SQL: ".$sql." >> ".mysql_error());
        }
    }
   
    $id=$_REQUEST['id']; //the user id
   
    //this query will select the user data which is to be used to fill up the form
    $sql="select * from search_reply where id={$id}";
    $rs=mysql_query($sql) or die("SQL: ".$sql." >> ".mysql_error());
    $num=mysql_num_rows($rs);
   
    //just a little validation, if a record was found, the form will be shown
    //it means that there's an information to be edited
    if($num>0){
        $row=mysql_fetch_assoc($rs);
        extract($row);
?>
<!--we have our html form here where new user information will be entered-->
<form action='#' method='post' border='0'>
    <table>
        <tr>
            <td>keyword</td>
            <td><input type='text' name='keyword' value='<?php echo $keyword;  ?>' /></td>
        </tr>
        <tr>
            <td>reply_msg</td>
            <td><input type='text' name='reply_msg'  value='<?php echo $reply_msg;  ?>' /></td>
        </tr>
            <td></td>
            <td>
                <!-- so that we could identify what record is to be updated -->
                <input type='hidden' name='id' value='<?php echo $id ?>' />
               
                <!-- we will set the action to edit -->
                <input type='hidden' name='action' value='edit' />
                <input type='submit' value='Edit' />
            </td>
        </tr>
    </table>
</form>
<?php
    }else{
        echo "<div>User with this id is not found.</div>";
    }
    echo "<a href='index.php'>Back To List</a>";
?>
