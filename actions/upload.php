<?php
perform_upload();
function perform_upload(){

    if ( ! empty( $_FILES['stock']['name'] ) ) {

        $errors = array();
        $file_name = $_FILES['stock']['name'];
        $file_tmp = $_FILES['stock']['tmp_name'];
        $file_type = $_FILES['stock']['type'];
    
        $file_data = explode( '.', $file_name );
        $file_ext = end( $file_data );
        $file_ext = strtolower( $file_ext );

        if ( ! empty( $file_ext ) ) {
            if( ! in_array( $file_ext, array( 'csv' ) ) ) {
                $errors = "Extension not supported.";
            }
        }
        if( empty( $errors ) ) {

            try {
                $dir = 'actions/upload-logs/';
                if ( ! is_dir( $dir ) ) {
                    mkdir( $dir, 0755, true );
                }
    
                move_uploaded_file( $file_tmp, $dir . $file_name );
    
                $result = [];
                $headers = [];
                $count = 0;
                $file = fopen($dir . $file_name,"r");
    
                while (($data = fgetcsv($file)) !== false)
                {
                    unset( $data[0] );
                    if( empty( $headers ) ) {
                        $headers = $data;
                    } else {
                        $result[] = array_combine( $headers, $data );
                    }
                }
                fclose($file);
                echo json_encode(
                    array(
                        'text'   => 'File uploaded successfully. Analysing Data please wait..',
                        'path'   => $dir . $file_name,
                        'data'   => $result,
                        'status' => 200
                    )
                );
            } catch (\Throwable $th) {
                echo json_encode(
                    array(
                        'text'   => 'Something Went wrong!!',
                        'errors' =>  $th->getMessage(),
                        'status' => 403
                    )
                );
            }
        } else {
            echo json_encode(
                array(
                    'text' => 'Something Went wrong!!',
                    'errors' =>  $errors,
                    'status' => 403
                )
            );
        }
    }
}