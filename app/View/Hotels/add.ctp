<h2>Add Hotel</h2>
<?php
echo $this->Form->create('Hotel');
echo $this->Form->input('name', array('label' => 'Hotel Name'));
echo $this->Form->input('email', array('label' => 'Email'));
echo $this->Form->input('phone_number', array('label' => 'Phone Number'));
echo $this->Form->input('address_id', array(
    'type' => 'select',
    'label' => 'Address',
    //'empty' => true, // add empty option to the beginning of the list
    'options' => $addressList // replace $addressList with your list of options
));echo $this->Form->hidden('owner_id', array('value' =>  $owner_id));
echo $this->Form->end('Save');
?>
