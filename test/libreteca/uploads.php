/*
Sadado de index.php
*/

            if($_SESSION[access]){
                echo '<form enctype="multipart/form-data" action="uploader.php" method="POST">&nbsp;';
                echo '<input type="hidden" name="MAX_FILE_SIZE" value="99999999999999999999"/>';
                echo '<span class="Estilo2">Choose files to upload: </span>';
                echo '<br>';

                for($i=0;$i<20;$i++){
                    echo '<input name="uploadedfile[]" type="file" size="80"/>';
                    echo '<br>';
                }
                echo '<input type="submit" value="Upload Files"/>';
                echo '</form>';
            }else{
                echo '<p><font color="#000000" face="Courier New">Please login with user and password</font></p>';
                echo '<br>';
                echo '<a href="signin.php">Ingresar</a>';
            }
