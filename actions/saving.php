<?php
class StockManagement {

    private $servername = 'localhost';
    private $database = 'kemuri';
    private $username = 'root';
    private $password = '';
    private $conn = '';
    private $current = '';
    public function __construct()
    {
        try {
            // Create connection.
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);
        } catch (\Throwable $th) {
            echo json_encode(
                array(
                    'errors' => $th->getMessage(),
                    'status' => 403
                )
            );
        }
    }

    public function syncData() {

        if (empty( $_POST['data'] )) {
            return array();
        }

        $stocks        = ! empty( $_POST['data'] ) ? $_POST['data'] : array();
        $randomKey     = array_rand($stocks, 1);
        $this->current = $stocks[ $randomKey ];
        $mayBeExists   = $this->search();
        switch ($mayBeExists) {
            case false:
                $this->createStock();
                break;
            
            default:
                try {
                    $this->updateStock( $mayBeExists );
                } catch (\Throwable $th) {
                    throw $th; die;
                }
                break;
        }
        unset( $stocks[$randomKey] );
        return $stocks;
    }

    private function search()
    {
        extract( $this->current );
        $sql = "SELECT * FROM `stocks` WHERE `stock_name` = '$stock_name'";
        $result = $this->conn->query($sql);
        if (! empty( $result->num_rows ) ) {
            return $result;
        } else {
            return false;
        }
    }

    private function createStock()
    {
        extract( $this->current );
        $data[$date] = $price;
        $data = serialize($data);
        $sql = "INSERT INTO `stocks`(`stock_name`, `history`) VALUES ('$stock_name','$data')";
        if ($this->conn->query($sql) === true) {
            return true;
        } else {
            echo "Error: " . $sql . "<br>" . $this->conn->error;
        }
    }

    private function updateStock( $result )
    {
        $row = $result->fetch_assoc();
        if( empty( $row ) ) return false;
        extract( $this->current );
        $id          = $row['id'];
        $data        = unserialize( $row['history'] );
        $data[$date] = $price;
        $data        = serialize($data);
        $sql         = "UPDATE `stocks` SET `history`='$data' WHERE `id` = $id";
        if ($this->conn->query($sql) === true) {
            return true;
        } else {
            echo "Error: " . $sql . "<br>" . $this->conn->error; die;
        }
    }

    public function getStock( $id = 'all' )
    {
        switch ($id) {
            case 'all':
                $sql = 'SELECT * FROM `stocks`';
                break;
            
            default:
                $sql = 'SELECT * FROM `stocks` WHERE `id` = ' . $id;
                break;
        }

        $result = $this->conn->query($sql);
        $return = [];
        if (!empty( $result->num_rows ) ) {
            while ($row = $result->fetch_assoc()) {
                $return[] = $row;
            }
        }
        return $return;
    }

    public function searchSingle($id)
    {
        $sql = "SELECT * FROM `stocks` WHERE `id` = '$id'";
        $result = $this->conn->query($sql);
        if (! empty( $result->num_rows ) ) {
            return $result->fetch_assoc();
        }
    }
}

// check if ajax.
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
    $obj = new StockManagement();

    if ( isset( $_POST['search'] ) ) {
        $id = $_POST['data'] ?? '';
        if ( empty( $id ) ) {
            echo json_encode([
                'success' => true,
                'data'    => array(),
                'message' => 'Nothing found'
            ]);
        } else {
            $stock = $obj->searchSingle($id);
            extract( $stock );
            $history = unserialize( $history );
            $dates   = array_keys( $history );
            usort( $dates, "compareDates");
            $parsed = [];
            foreach ( $dates as $key => $value ) {
                $parsed[ $value ] = $history[$value];
            }

            $amounts = (array_values($parsed));
            sort($amounts);
            $mean_price         = array_sum( $parsed )/count( $parsed );
            $standard_deviation = std_deviation( $parsed );
            $today_profit       = ($mean_price) - $parsed[array_key_last( $parsed )];
            $highest_price      = $amounts[array_key_last( $amounts )];

            $result = compact( 'mean_price', 'standard_deviation', 'today_profit', 'highest_price' );
            echo json_encode([
                'success' => true,
                'history' => getHtml($parsed),
                'numbers' => $result
            ]);
        }
    } else {
        $stocks =  $obj->syncData();
        echo json_encode([
            'success' => true,
            'data'    => $stocks,
            'count'   =>  count($stocks)
        ]);
    }
}

function compareDates($date1, $date2){
    return strtotime($date1) - strtotime($date2);
}

function std_deviation($my_arr)
{
   $no_element = count($my_arr);
   $var = 0.0;
   $avg = array_sum($my_arr)/$no_element;
   foreach($my_arr as $i)
   {
      $var += pow(($i - $avg), 2);
   }
   return (float)sqrt($var/$no_element);
}

function getHtml($history = array()) {
    ob_start();
    ?>
    <div class="card">
    <div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped">
        <thead>
            <tr>
            <th> Date </th>
            <th> Rate </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $history as $date => $price) : ?>
            <tr>
                <td> <?php echo $date; ?> </td>
                <td><?php echo '$' . $price; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
    </div>
    </div>
    <?php
    return ob_get_clean();
}