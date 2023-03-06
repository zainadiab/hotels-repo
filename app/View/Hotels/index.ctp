<h3><?php echo $pageTitle; ?></h3>

<h2>List your property</h2>

<!-- Display the login and register buttons, linking to the appropriate pages -->
<div class="login-box">
  <?php echo $this->Html->link(
    'Login',
    'http://users/users/login',
    array('class' => 'login-button')
  ); ?>
</div>

<div class="register-box">
  <?php echo $this->Html->link(
    'Register',
    'http://users/users/register',
    array('class' => 'register-button')
  ); ?>
</div>
<h3> </h3>
<h3> </h3>

<h3>Select your destination:</h3>
<?php 
echo $this->Form->create('Hotel', array('url' => array('controller' => 'hotels', 'action' => 'index')));
echo $this->Form->input('address_id', array('type' => 'select', 'label' => 'Address', 'options' => $addressList));
  echo $this->Form->input('room_type', array('type' => 'select', 'label' => 'Room Type', 'options' => array(1 =>'Single', 2=> 'Double', 3 =>'Suite')));
?>

    <?php echo $this->Form->submit('Search', array('class' => 'search-button')); 
    echo $this->Form->end();
    ?>
  </div>
</div>

<h3>  </h3>
<h3>  </h3>
<h3>  </h3>

<!-- Display the search results -->
<table> 
       <thead> 
               <tr> 
                    <th>Num</th>
                    <th>Hotel</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Price</th>
                    <th>Action </th>
            </tr>
        </thead>
        <tbody>
        <?php if(isset($hotels)){ $index = 1;?>
                <?php foreach ($hotels as $h => $hotel): ?>
                    <tr>
                   <td><?php echo h('Room '.$index++); ?></td>
                       <td><?php echo h($hotel['Hotel']['name']); ?></td>
                       <td><?php echo h($hotel['Hotel']['address_name']); ?></td>
                      <td><?php echo h($hotel['Hotel']['phone_number']); ?></td>       
                      <td><?php echo h($hotel['Hotel']['email']); ?></td>
                      <td><?php echo h($hotel['Hotel']['price']); ?></td>
                        <td> <?php echo $this->Html->link('Make reservation','http://bookingproject/hotels/reserve?id='.$hotel['Hotel']['room_id']); ?>
                      </td>
                  </tr>
               <?php endforeach; }?> 
       </tbody>
</table>



