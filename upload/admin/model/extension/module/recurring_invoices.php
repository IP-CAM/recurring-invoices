<?php
 
class ModelExtensionModuleRecurringInvoices extends Model {
   public function createSchema() {
      $this->db->query("
      CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cycling_payments` (
      `ID` int(11) NOT NULL AUTO_INCREMENT,
      `userId` int(11) NOT NULL,
      `vmID` int(11) DEFAULT NULL,
      `prodID` int(11) NOT NULL,
      `startingDate` date NOT NULL,
      `expiringDate` date DEFAULT NULL,
      PRIMARY KEY (`ID`),
      UNIQUE KEY `userId` (`userId`)
      ) DEFAULT CHARSET=utf8");

      $this->db->query("
      CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "vms` (
      `ID` int(11) NOT NULL AUTO_INCREMENT,
      `userid` int(11) NOT NULL,
      `vmName` varchar(100) NOT NULL,
      `vmIp` varchar(100) DEFAULT NULL,
      `Host` varchar(20) DEFAULT NULL,
      `Status` enum('RUN','PEN','SUSP','STOP') NOT NULL DEFAULT 'PEN',
      PRIMARY KEY (`ID`),
      UNIQUE KEY `vmName` (`vmName`)

      ) DEFAULT CHARSET=utf8");
    
      $this->db->query("

      CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cycling_invoices` (
      `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
      `invoiceNumber` varchar(20) DEFAULT NULL,
      `customer_id` varchar(100) NOT NULL,
      `txnid` varchar(100) DEFAULT NULL,
      `status_id` enum('Y','N') DEFAULT NULL,
      `amount` float NOT NULL,
      `order_id` int(11) DEFAULT NULL,
      `date_added` date NOT NULL,
      `datePayed` date DEFAULT NULL,
      `dateExpire` date DEFAULT NULL,
      `factPeriod` varchar(30) DEFAULT NULL,
      PRIMARY KEY (`invoice_id`)

      ) DEFAULT CHARSET=utf8");
   }
   
   public function deleteSchema() {
      $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "cycling_payments`");
       $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "vms`");
       $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "cycling_invoices`");
   }
}
 
?>
