<?php
function key2kolom($key)
{
  return ucwords(str_replace('_', ' ', $key));
}
