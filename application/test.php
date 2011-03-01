<?php

class PartyMember implements Iterator
{
	    private $dbRow;

		    public function __construct($memberId)
			    {
					        // Get a row from the database with $memberId;
							        // store to $dbRow
									    }

										    public function getName()
											    {
													        return $this->dbRow['name'];
															    }

																    public function current()
																	    {
																			        return current($this->dbRow);
																					    }

																						    public function key()
																							    {
																									        return key($this->dbRow);
																											    }

																												    public function next()
																													    {
																															        next($this->dbRow);
																																	    }

																																		    public function rewind()
																																			    {
																																					        reset($this->dbRow);
																																							    }

																																								    public function valid()
																																									    {
																																											        return (current($this->dbRow) !== FALSE);
																																													    }
}

$member = new PartyMember(1);

foreach ($member as $key => $value) {
	    echo "{$key} --> {$value}\n";
}

?>
