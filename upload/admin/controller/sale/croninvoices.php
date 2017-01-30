<?php
class ControllerSaleCroninvoices extends Controller {

          public function index() {
              $this->load->model('sale/invoice');
              $this->load->model('checkout/invoice');
              //Run a 10 days before check
              $expiring_plan = $this->model_sale_invoice->getExpiringPayment(10);
              if($expiring_plan){
                  foreach ($expiring_plan as $plan){
                      $data=array();
                      $data['order_id'] = $plan['order_id'];
                      $data['expiringDate'] = $plan['expiringDate'];
                      $data['customer_id'] = $plan['userId'];
                      $data['product_id'] = $plan['product_id'];
                      $data['cycliing_id'] = $plan['ID'];
                      $data['order_product_id'] = $plan['order_product_id'];
                      $months = $this->model_checkout_invoices->getMonthsPayed($order_id, $data['order_product_id'] ); 
                      $sub_period = " +" .$months . " months";

                      $expiration_date= strtotime($sub_period, strtotime($plan['expiringDate']));
                      $data['next_expiration_date'] = date ( 'Y-m-d' , $expiration_date );
                      $data['fact_rediod'] = $plan['expiringDate'] . ' - ' . $data['next_expiration_date'];
                      //$order_products=$this->model_account_order->getOrderProducts($plan['order_id']);
                      //Check if there is already an invoice for the period and 
                      //If not create one
                      // If status of existing invoice is unpayed send a reminder
                      $invoice_exists = $this->model_sale_invoice->checkExistingCycleInvoice($data);
                              
                      if (!$invoice_exists) {
                        //Create the invoice for the new cycling paymente
                        $data['cycliing_id'] = $plan['ID'];
                        $data['txnid'] = '0';
                        $data['status_id'] = 1;
                        $order_data = $this->model_checkout_invoice->getOrderProductById($data);
                        $data['amount'] = $order_data[0]['total'];
                        $order_products=$this->model_account_order->getOrderProducts($plan['order_id']);
                        $new_invoice =  $this->model_checkout_invoice->addInvoice($data,$product_id,$iscron='si'); 
                        //TODO
                        //Send mail of new invoice creation
                      }//TODO Now send mail for old unpayed invoices



                      }
                  }
              }
          }




}
