<?php
echo LIMETICKET_Helper::PageStylePopup(true);
echo LIMETICKET_Helper::PageTitlePopup("EMail Ticket", "Sent");
?>

Ticket has been sent to <?php echo $this->postData['email']['email']; ?> 

<?php echo LIMETICKET_Helper::PageStylePopupEnd(); ?>
