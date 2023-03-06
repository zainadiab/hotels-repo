<h2>Edit Hotel</h2>
<?php echo $this->Html->link('Home', 'http://users/users/index?owner_id=' .$hotel[0]['Hotel']['owner_id'], array('class' => 'add-button')); ?>

<?php
echo $this->Form->create('Hotel', array('url' => array('action' => 'edit', $id)));
echo $this->Form->input('name', array('label' => 'Name', 'value' => $hotel[0]['Hotel']['name']));
echo $this->Form->input('phone_number', array('label' => 'Phone number', 'value' => $hotel[0]['Hotel']['phone_number']));
echo $this->Form->input('email', array('label' => 'Email', 'value' => $hotel[0]['Hotel']['email']));
echo $this->Form->input('address_id', array(
    'type' => 'select',
    'label' => 'Address',
    //'empty' => true, // add empty option to the beginning of the list
    'options' => $addressList // replace $addressList with your list of options
));
echo $this->Form->hidden('owner_id', array('value' => $hotel[0]['Hotel']['owner_id']));
echo $this->Form->hidden('deleted', array('value' => $hotel[0]['Hotel']['deleted']));
echo $this->Form->hidden('id', array('value' => $id));

echo $this->Form->end('Update');
?>
