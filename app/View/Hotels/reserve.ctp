<h2>Reserve Room <?php echo $room_id; ?></h2>

<?php echo $this->Form->create('Reservation', array('class' => 'form-horizontal')); ?>
    <?php echo $this->Form->hidden('room_id', array('value' => $room['Room']['id'])); ?>

    <div class="control-group">
        <?php echo $this->Form->label('full_name', 'Full Name', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $this->Form->input('full_name', array('class' => 'input-xlarge', 'label' => false)); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $this->Form->label('phone_number', 'Phone Number', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $this->Form->input('phone_number', array('class' => 'input-xlarge', 'label' => false)); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $this->Form->label('start_date', 'Check-In Date', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $this->Form->input('start_date', array('class' => 'input-xlarge datepicker', 'label' => false)); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $this->Form->label('end_date', 'Check-Out Date', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $this->Form->input('end_date', array('class' => 'input-xlarge datepicker', 'label' => false)); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $this->Form->label('payment_method', ' ', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php echo $this->Form->radio('payment_method', array('visa' => 'Visa', 'cash' => 'Cash'), null, array('label' => false)); ?>
        </div>
    </div>

    <div class="form-actions">
        <?php echo $this->Form->submit('Reserve', array('class' => 'btn btn-primary')); ?>
        <?php echo $this->Html->link('Cancel', array('action' => 'index'), array('class' => 'btn')); ?>
    </div>
<?php echo $this->Form->end(); ?>
