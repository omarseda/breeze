<?php 

class Toknizr {

    public $rx = "[(?: 
          (?<__space_h_____>\h+)
        | (?<__space_v_____>(?:\\n\\r|\\r\\n|\\n|\\r))
        | (?<__com_ln______>//)
        | (?<__com_b_opn___>/\*)
        | (?<__com_b_clo___>\*/)
        | (?<__com_ln_alt__>\#)
        | (?<__esc_str_d___>\\\\\")       # string escape
        | (?<__esc_str_s___>\\\\')        # string escape
        | (?<__esc_str_b___>\\\\`)        # string escape
        | (?<__bslash_db___>\\\\\\\\)     # two backslash
        | (?<__backslash___>\\\\)         # one backslash
        | (?<__str_d_______>\")
        | (?<__str_s_______>')
        | (?<__str_b_______>`)
        | (?<__b_curly_o___>\{)
        | (?<__b_curly_c___>\})
        | (?<__b_angle_o___>\[)
        | (?<__b_angle_c___>\])
        | (?<__b_paren_o___>\()
        | (?<__b_paren_c___>\))
        | (?<__php_begin___><\?php)
        | (?<__php_var_____>\\$\w+)
        | (?<__lang_3______>\.{3})
        | (?<__opr_rel_3___>(?:===|!==))
        | (?<__opr_bit_3___>>>>)
        | (?<__asgn_2______>(?:\+=|-=|/=|\*=|\.=))
        | (?<__opr_bit_2___>(?:>>|<<))
        | (?<__opr_ari_2___>(?:\+\+|--))
        | (?<__opr_rel_2___>(?:==|!=|>=|<= ))
        | (?<__opr_log_2___>(?:&{2}|\|{2}))        # ----------- && or ||
        | (?<__punct_sn____>(@|,|\.|\?|:|;))
        | (?<__pun_asgn_1__>=)
        | (?<__opr_bit_1___>(&|\||\^))           # -------- & , | , ^
        | (?<__opr_ari_1___>(\*|/|\+|-|% ))   #  -----------  * / + - ^ % 
        | (?<__opr_rel_1___>(<|>))
        | (?<__opr_log_1___>!)
        | (?<__num_int_____>\d+)                 # baru integer
        | (?<__word________>([a-zA-Z_]+[a-zA-Z_0-9]*))
        | (?<__keyword_____>(?:if|else|for))
        | (?<__other_______>.+?)
    )]sx";

    public $tokenlist = [];
    public $source;

    public function __construct ($filename) {
        $this->source = file_get_contents($filename);
    }

    public function tokenize() {
        preg_replace_callback ($this->rx, function($m) {
            $tmp = [];
            foreach ($m as $rxCapturer => $captured) {
                if (is_string($rxCapturer)) {
                    $tmp['tokentype'] = $rexCapturer;
                    if ($rexCapturer == '__num_int_____') {
                        $tmp['content'] = $captured;
                        // break;
                    } 
                    if (!empty($captured)) { // ERROR: jika $captured berupa integer 0
                        $tmp['content'] = $captured;
                        break;
                    }
                }
            }
            $this->tokenlist[] = $tmp; 
        }, 
        $this->source);
    }

    public function test() {
        $count = count($this->tokenlist);
        for ($i = 0; $i < $count; $i++) {
            $token = $this->tokenlist[$i];
            if ($token['tokentype'] == '__space_v_____' or $token['tokentype'] == '__space_h_____') {
                // print $token['content'];
                // print "\n";
            } else {
                print $token['tokentype'];
                print ": ";
                print $token['content'];
                print "\n";
            }
        }
    }
    #eoc
}

$test = new Toknizr("tes.php");
$test->tokenize();
$test->test();
