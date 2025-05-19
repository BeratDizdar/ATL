<?php

main();

$pos = 0;
$debug = 0;

function main() {
    global $pos;
    $pos = 0;
	$tape = array_fill(0, 1000, null);
    echo "ATL - REPL\n";
    while(1) {
        echo "\n>> ";
        l_eval(l_read(), $tape);
    }        
}

function l_read() {
    $input = trim(fgets(STDIN));
    $commands = preg_split('/(?=:)/', $input, -1, PREG_SPLIT_NO_EMPTY);
    $lang = '/:(--|\+\+)|:(!=|<|>|=)\?-?\d+|:p(-?\d+|[\"\'](.*?)[\'\"])|:(<|>|w|r|q|c|x|d|v)/';
    $all_tokens = [];
    foreach ($commands as $cmd) {
        $cmd = trim($cmd);
        preg_match_all($lang, $cmd, $tokens);
        if (!empty($tokens[0])) {
            $all_tokens = array_merge($all_tokens, $tokens[0]);
        }
    }
    return [$all_tokens];
}

function init_fn_list(&$tape) {
    global $debug, $pos;
    $fn_list = [
        ":--" => function () use (&$tape, &$pos) {$tape[$pos]--;},
        ":++" => function () use (&$tape, &$pos) {$tape[$pos]++;},
		":<" => function () use (&$pos) { $pos -= 1; },
		":>" => function () use (&$pos) { $pos += 1; },
		":d" => function () use (&$debug) { $debug = 1; },
		":w" => function () use (&$tape, &$pos) {print($tape[$pos] . "\n");},
		":r" => function () use (&$tape, &$pos) {
			print("<< ");
			$input = trim(fgets(STDIN));
			if (is_numeric($input)) {$tape[$pos] = (int)$input;} 
			else {$tape[$pos] = $input;}
		},
		":q" => fn() => exit(0),
		":c" => fn() => print("\033[H\033[0J"),
		":v" => function () use (&$tape, &$pos) {
			for ($i = 0; $i < 10; $i++)
				print($tape[$pos+$i] . "\n");
		},
	];

    return $fn_list;   
}

function l_eval($tokens, &$tape) {
    global $debug, $pos;
    $fn_list = init_fn_list($tape, $pos);
    
    for ($tp = 0; $tp < count($tokens[0]); $tp++) {
        $token = $tokens[0][$tp];
		if ($debug) print_token($pos, $tp, $tokens);
        
		// P özelinde
        if (str_starts_with($token, ":p")) {
            $arg = substr($token, 2);
            $arg = trim($arg, "\"'");
            if (is_numeric($arg)) {
                $arg = (int)$arg;
            }
            $tape[$pos] = $arg;
            continue;
        }
		
		// Atlama özelinde
		if (preg_match('/:(!=|=|<|>)\?(-?\d+)/', $token, $matches)) {
			$op = $matches[1];
			$offset = (int)$matches[2];
			$val = $tape[$pos];

			$jump = false;
			if ($op == '=' && $val == 0) $jump = true;
			elseif ($op == '!=' && $val != 0) $jump = true;
			elseif ($op == '<' && $val < 0) $jump = true;
			elseif ($op == '>' && $val > 0) $jump = true;

			if ($jump) {
				$tp += $offset - 1;
			}
			continue;
		}
		
        if (isset($fn_list[$token])) {
            $fn_list[$token]();
        } else {
            echo "unknown command: $token\n";
        }
	}
}

function print_token($pc, $tp, $tokens) {    
    echo "[cell:$pc][pc:$tp]-> ",$tokens[0][$tp], " \n";
}

