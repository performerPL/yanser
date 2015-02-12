<?php /* Smarty version 2.6.18, created on 2011-12-21 23:13:53
         compiled from core/paging_get.html */ ?>
	<table>
		<tr>
			<td>
				<?php if ($this->_tpl_vars['pager']->prev > 0): ?>
				<a name="page" href="<?php echo $this->_tpl_vars['out']['paging']->url; ?>
?page=<?php echo $this->_tpl_vars['out']['paging']->prev; ?>
">
					<< Wstecz
				</a>	 
				<?php endif; ?>
				<?php unset($this->_sections['page']);
$this->_sections['page']['name'] = 'page';
$this->_sections['page']['loop'] = is_array($_loop=$this->_tpl_vars['out']['paging']->numPages) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['page']['show'] = true;
$this->_sections['page']['max'] = $this->_sections['page']['loop'];
$this->_sections['page']['step'] = 1;
$this->_sections['page']['start'] = $this->_sections['page']['step'] > 0 ? 0 : $this->_sections['page']['loop']-1;
if ($this->_sections['page']['show']) {
    $this->_sections['page']['total'] = $this->_sections['page']['loop'];
    if ($this->_sections['page']['total'] == 0)
        $this->_sections['page']['show'] = false;
} else
    $this->_sections['page']['total'] = 0;
if ($this->_sections['page']['show']):

            for ($this->_sections['page']['index'] = $this->_sections['page']['start'], $this->_sections['page']['iteration'] = 1;
                 $this->_sections['page']['iteration'] <= $this->_sections['page']['total'];
                 $this->_sections['page']['index'] += $this->_sections['page']['step'], $this->_sections['page']['iteration']++):
$this->_sections['page']['rownum'] = $this->_sections['page']['iteration'];
$this->_sections['page']['index_prev'] = $this->_sections['page']['index'] - $this->_sections['page']['step'];
$this->_sections['page']['index_next'] = $this->_sections['page']['index'] + $this->_sections['page']['step'];
$this->_sections['page']['first']      = ($this->_sections['page']['iteration'] == 1);
$this->_sections['page']['last']       = ($this->_sections['page']['iteration'] == $this->_sections['page']['total']);
?>
					<?php echo $this->_tpl_vars['out']['paging']->sign; ?>

					<a name="page" href="<?php echo $this->_tpl_vars['out']['paging']->url; ?>
?page=<?php echo $this->_sections['page']['iteration']; ?>
">
					
						<?php if ($this->_sections['page']['iteration'] == $this->_tpl_vars['out']['paging']->page): ?>
							<b><?php echo $this->_tpl_vars['out']['paging']->offset+1; ?>
 - 
							<?php if ($this->_sections['page']['last']): ?>
							 <?php echo $this->_tpl_vars['out']['paging']->numHits; ?>

							<?php else: ?>
							 <?php echo $this->_tpl_vars['out']['paging']->offset+$this->_tpl_vars['out']['paging']->limit; ?>

							<?php endif; ?>
							</b>
						<?php else: ?>
							<?php echo $this->_sections['page']['iteration']; ?>

						<?php endif; ?>
					</a>
					<?php echo $this->_tpl_vars['out']['paging']->sign; ?>

				<?php endfor; endif; ?>
				<?php if ($this->_tpl_vars['out']['paging']->next > 0): ?>
				<a name="page" href="<?php echo $this->_tpl_vars['out']['paging']->url; ?>
?page=<?php echo $this->_tpl_vars['out']['paging']->next; ?>
">
					Następny >>
				</a>
				<?php endif; ?>
			</td>
		</tr>
	</table>