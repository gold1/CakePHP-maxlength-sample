
<?= $this->Form->create(); ?>

<?= $this->Form->input('It.name', array(
	'type' => 'text',
)); ?>

<?= $this->Form->input('It.note', array(
	'type' => 'textarea',
)); ?>

<?= $this->Form->submit('送信'); ?>

