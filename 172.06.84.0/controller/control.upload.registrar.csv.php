<?php 

    // UPLOAD CSV FILE
    require_once("../../connection/conn.php");


    if (isset($_POST['csv_hidden_field'])) {
        $error = '';
        $total_line = '';

        if ($_FILES['csvfile']['name'] != '') {

            $allowed_extension = array('csv', 'txt');
            $file_array = explode('.', $_FILES['csvfile']['name']);
            $extension = end($file_array);

            if (in_array($extension, $allowed_extension)) {

                $new_file_name = rand() . '.' . $extension;
                $_SESSION['csv_file_name'] = $new_file_name;
                $location = BASEURL . 'media/uploadedregistrars/'.$new_file_name;

                move_uploaded_file($_FILES['csvfile']['tmp_name'], $location);

                $file_content = file($location, FILE_SKIP_EMPTY_LINES);
                $total_line = count($file_content);

            } else {
                $error = 'Only CSV file format is alloed!';
            }

        } else {
            $error = 'Please select a file!';
        }

        if ($error != '') {
            $output = array(
                'error' => $error
            );
        } else {
            $output  = array(
                'success' => true,
                'total_line' => ($total_line - 1)
            );
        }

        echo json_encode($output);
    }
