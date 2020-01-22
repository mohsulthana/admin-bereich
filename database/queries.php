<?php

namespace Gemueseeggli\Database;

use Gemueseeggli\Database\Model;
use Gemueseeggli\Database\Model\User;

require_once(__DIR__ . '/../database/model/User.php');

class Queries
{
    public function getUsers($em, $top, $skip, $title, $orderBy, $orderByDesc)
    {
        $orderByDesc = $orderByDesc === 'true' ? true: false;
        $title = strtolower($title);
        $userRepository = $em->getRepository('Gemueseeggli\Database\Model\User');
        $users = $userRepository->findAll();
        if ($title != '') {
            $filteredUsers = array();
            foreach ($users as $key => $user) {
                $username = strtolower($user->getUsername());
                $billingAddress = $user->getBillingAddress();
                if (strpos($username, $title) !== false) {
                    $filteredUsers[] = $user;
                    continue;
                } elseif ($billingAddress != null) {
                    $firstname = strtolower($billingAddress->getFirstname());
                    $name = strtolower($billingAddress->getName());
                    $street = strtolower($billingAddress->getStreet());
                    $zip = (string)$billingAddress->getZip();
                    $town = strtolower($billingAddress->getTown());


                    if (strpos($firstname, $title) !== false) {
                        $filteredUsers[] = $user;
                        continue;
                    } elseif (strpos($name, $title) !== false) {
                        $filteredUsers[] = $user;
                        continue;
                    } elseif (strpos($street, $title) !== false) {
                        $filteredUsers[] = $user;
                        continue;
                    } elseif (strpos($zip, $title) !== false) {
                        $filteredUsers[] = $user;
                        continue;
                    } elseif (strpos($town, $title) !== false) {
                        $filteredUsers[] = $user;
                        continue;
                    }
                }
                $region = $user->getRegion();
                if ($region != null) {
                    $regionName = strtolower($region->getName());
                    if (strpos($regionName, $title) !== false) {
                        $filteredUsers[] = $user;
                        continue;
                    }
                }
            }
            $users = $filteredUsers;
        }

        $orderByIndex = intval($orderBy);

        switch ($orderByIndex) {
            case 0:
                usort($users, function ($a, $b) use ($orderByDesc) {
                    if (!$orderByDesc) {
                        return strcmp($a->getSortValue(), $b->getSortValue());
                    } else {
                        return strcmp($b->getSortValue(), $a->getSortValue());
                    }
                });
                break;
            case 1:
                usort($users, function ($a, $b) use ($orderByDesc) {
                    if (!$orderByDesc) {
                        if ($a->region === null && $b->region === null) {
                            return 0;
                        }
                        if ($a->region !== null && $b->region === null) {
                            return 1;
                        }
                        if ($a->region === null && $b->region !== null) {
                            return -1;
                        }
                        return strcmp(strtolower($a->region->name), strtolower($b->region->name));
                    } else {
                        if ($a->region === null && $b->region === null) {
                            return 0;
                        }
                        if ($a->region !== null && $b->region === null) {
                            return -1;
                        }
                        if ($a->region === null && $b->region !== null) {
                            return 1;
                        }
                        return strcmp(strtolower($b->region->name), strtolower($a->region->name));
                    }
                });
                break;
            case 2:
                usort($users, function ($a, $b) use ($orderByDesc) {
                    if (!$orderByDesc) {
                        if ($a->billingAddress === null && $b->billingAddress === null) {
                            return 0;
                        }
                        if ($a->billingAddress !== null && $b->billingAddress === null) {
                            return 1;
                        }
                        if ($a->billingAddress === null && $b->billingAddress !== null) {
                            return -1;
                        }
                        return strcmp(strtolower($a->billingAddress->street), strtolower($b->billingAddress->street));
                    } else {
                        if ($a->billingAddress === null && $b->billingAddress === null) {
                            return 0;
                        }
                        if ($a->billingAddress !== null && $b->billingAddress === null) {
                            return -1;
                        }
                        if ($a->billingAddress === null && $b->billingAddress !== null) {
                            return 1;
                        }
                        return strcmp(strtolower($b->billingAddress->street), strtolower($a->billingAddress->street));
                    }
                });
                break;
            case 3:
                usort($users, function ($a, $b) use ($orderByDesc) {
                    if (!$orderByDesc) {
                        if ($a->billingAddress === null && $b->billingAddress === null) {
                            return 0;
                        }
                        if ($a->billingAddress !== null && $b->billingAddress === null) {
                            return 1;
                        }
                        if ($a->billingAddress === null && $b->billingAddress !== null) {
                            return -1;
                        }
                        if ($a->billingAddress->zip === $b->billingAddress->zip) {
                            return 0;
                        }
                        if ($a->billingAddress->zip > $b->billingAddress->zip) {
                            return 1;
                        }
                        if ($a->billingAddress->zip < $b->billingAddress->zip) {
                            return -1;
                        }
                    } else {
                        if ($a->billingAddress === null && $b->billingAddress === null) {
                            return 0;
                        }
                        if ($a->billingAddress !== null && $b->billingAddress === null) {
                            return -1;
                        }
                        if ($a->billingAddress === null && $b->billingAddress !== null) {
                            return 1;
                        }
                        if ($a->billingAddress->zip === $b->billingAddress->zip) {
                            return 0;
                        }
                        if ($a->billingAddress->zip > $b->billingAddress->zip) {
                            return -1;
                        }
                        if ($a->billingAddress->zip < $b->billingAddress->zip) {
                            return 1;
                        }
                    }
                });
                break;
            case 4:
                usort($users, function ($a, $b) use ($orderByDesc) {
                    if (!$orderByDesc) {
                        if ($a->billingAddress === null && $b->billingAddress === null) {
                            return 0;
                        }
                        if ($a->billingAddress !== null && $b->billingAddress === null) {
                            return 1;
                        }
                        if ($a->billingAddress === null && $b->billingAddress !== null) {
                            return -1;
                        }
                        return strcmp(strtolower($a->billingAddress->town), strtolower($b->billingAddress->town));
                    } else {
                        if ($a->billingAddress === null && $b->billingAddress === null) {
                            return 0;
                        }
                        if ($a->billingAddress !== null && $b->billingAddress === null) {
                            return -1;
                        }
                        if ($a->billingAddress === null && $b->billingAddress !== null) {
                            return 1;
                        }
                        return strcmp(strtolower($b->billingAddress->town), strtolower($a->billingAddress->town));
                    }
                });
                break;
        }
        return $users;
    }
}
