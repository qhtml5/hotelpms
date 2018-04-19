<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

</style>
<div class="alert alert-danger">
    <strong><?= $message ?></strong>
  </div>
