<?php

$data = [
    ['a', 'a', '8'],
    ['0.167', 'a', '2'],
    ['a', 'a', 'a']
];

// for ($i=0; $i < 3; $i++) {
//     for ($u=0; $u < 3; $u++) { 
//         if ($i == $u) {
//             $data[$i][$u] = 1;
//         } else {
//             $data[$u][$i] = 1/$data[$i][$u];
//         }
//         echo $data[$i][$u] . ' ';
//     } 
//     echo "</br>";
// }
for ($i = 0; $i < 3; $i++) {
    for ($u = $i; $u < 3; $u++) {
        if ($i == $u) {
            $data[$i][$u] = 1;
        } elseif ($data[]) {
            $data[$u][$i] = 1 / $data[$i][$u];
        }
    }
    echo "</br>";
}

for ($i = 0; $i < 3; $i++) {
    for ($u = 0; $u < 3; $u++) {
        echo $data[$i][$u];
    }
    echo "</br>";
}
