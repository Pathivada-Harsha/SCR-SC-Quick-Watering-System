<tr>
	<td class="fw-bold"><?php echo strtoupper(htmlspecialchars($r['motor_id'])); ?></td>
	<td><?php echo htmlspecialchars($r['date_time']); ?></td>
	<td><?php echo number_format((float)$r['inlet_pressure'], 2); ?></td>
	<td><?php echo number_format((float)$r['outlet_pressure1'], 2); ?></td>
	<td><?php echo number_format((float)$r['outlet_pressure2'], 2); ?></td>
	<td class="text-center">
		<span class="status-badge <?php echo ($r['on_off_status'] == 1) ? 'status-on' : 'status-off'; ?>">
			<i class="fas fa-power-off me-1"></i><?php echo ($r['on_off_status'] == 1) ? 'ON' : 'OFF'; ?>
		</span>
	</td>
	<td><?php echo number_format((float)$r['r_y_voltage'], 1); ?></td>
	<td><?php echo number_format((float)$r['y_b_voltage'], 1); ?></td>
	<td><?php echo number_format((float)$r['b_r_voltage'], 1); ?></td>

	<td><?php echo number_format((float)$r['motor_voltage'], 1); ?></td>

	<td><?php echo number_format((float)$r['motor_current'], 2); ?></td>
	<td><?php echo number_format((float)$r['energy_kwh'], 1); ?></td>
	<td><?php echo number_format((float)$r['flow_rate'], 2); ?></td>
	<td><?php echo number_format((float)$r['speed'], 3); ?></td>
	<td><?php echo number_format((float)$r['reference_frequency'], 2); ?></td>

	<td><?php echo number_format((float)$r['frequency'], 2); ?></td>

	<td><?php echo number_format((float)$r['total_running_hours'], 1); ?></td>
	<td class="text-center">
		<span class="status-badge <?php echo ($r['pf_1_2'] == 1) ? 'status-on' : 'status-off'; ?>">
			<?php if ($r['pf_1_2'] == 1): ?>
				<i class="fas fa-toggle-off me-1"></i>OPEN
			<?php else: ?>
				<i class="fas fa-toggle-on me-1"></i>CLOSE
			<?php endif; ?>
		</span>
	</td>
	<td class="text-center">
		<span class="status-badge <?php echo ($r['pf_3_4'] == 1) ? 'status-on' : 'status-off'; ?>">
			<?php if ($r['pf_3_4'] == 1): ?>
				<i class="fas fa-toggle-off me-1"></i>OPEN
			<?php else: ?>
				<i class="fas fa-toggle-on me-1"></i>CLOSE
			<?php endif; ?>
		</span>
	</td>
	<td class="text-center">
		<span class="status-badge <?php echo ($r['pf_5_6'] == 1) ? 'status-on' : 'status-off'; ?>">
			<?php if ($r['pf_5_6'] == 1): ?>
				<i class="fas fa-toggle-off me-1"></i>OPEN
			<?php else: ?>
				<i class="fas fa-toggle-on me-1"></i>CLOSE
			<?php endif; ?>
		</span>
	</td>
	<td class="text-center">
		<span class="status-badge <?php echo ($r['pf_7'] == 1) ? 'status-on' : 'status-off'; ?>">
			<?php if ($r['pf_7'] == 1): ?>
				<i class="fas fa-toggle-off me-1"></i>OPEN
			<?php else: ?>
				<i class="fas fa-toggle-on me-1"></i>CLOSE
			<?php endif; ?>
		</span>
	</td>
	<td class="text-center">
		<span class="status-badge <?php echo ($r['pf_8'] == 1) ? 'status-on' : 'status-off'; ?>">
			<?php if ($r['pf_8'] == 1): ?>
				<i class="fas fa-toggle-off me-1"></i>OPEN
			<?php else: ?>
				<i class="fas fa-toggle-on me-1"></i>CLOSE
			<?php endif; ?>
		</span>
	</td>
	<td class="text-center">
		<span class="status-badge <?php echo ($r['pf_9_10'] == 1) ? 'status-on' : 'status-off'; ?>">
			<?php if ($r['pf_9_10'] == 1): ?>
				<i class="fas fa-toggle-off me-1"></i>OPEN
			<?php else: ?>
				<i class="fas fa-toggle-on me-1"></i>CLOSE
			<?php endif; ?>
		</span>
	</td>

</tr>