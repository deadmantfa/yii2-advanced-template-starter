<?php
/** Adminer - Compact database management
 * @link https://www.adminer.org/
 * @author Jakub Vrana, https://www.vrana.cz/
 * @copyright 2007 Jakub Vrana
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
 * @version 4.7.3
 */
error_reporting(6135);
$Vc = !preg_match('~^(unsafe_raw)?$~', ini_get("filter.default"));
if ($Vc || ini_get("filter.default_flags")) {
    foreach (array('_GET', '_POST', '_COOKIE', '_SERVER') as $X) {
        $Hi = filter_input_array(constant("INPUT$X"), FILTER_UNSAFE_RAW);
        if ($Hi) $$X = $Hi;
    }
}
if (function_exists("mb_internal_encoding")) mb_internal_encoding("8bit");
function
connection()
{
    global $g;
    return $g;
}

function
adminer()
{
    global $b;
    return $b;
}

function
version()
{
    global $ia;
    return $ia;
}

function
idf_unescape($v)
{
    $ne = substr($v, -1);
    return
        str_replace($ne . $ne, $ne, substr($v, 1, -1));
}

function
escape_string($X)
{
    return
        substr(q($X), 1, -1);
}

function
number($X)
{
    return
        preg_replace('~[^0-9]+~', '', $X);
}

function
number_type()
{
    return '((?<!o)int(?!er)|numeric|real|float|double|decimal|money)';
}

function
remove_slashes($qg, $Vc = false)
{
    if (get_magic_quotes_gpc()) {
        while (list($z, $X) = each($qg)) {
            foreach ($X
                     as $de => $W) {
                unset($qg[$z][$de]);
                if (is_array($W)) {
                    $qg[$z][stripslashes($de)] = $W;
                    $qg[] =& $qg[$z][stripslashes($de)];
                } else$qg[$z][stripslashes($de)] = ($Vc ? $W : stripslashes($W));
            }
        }
    }
}

function
bracket_escape($v, $Oa = false)
{
    static $ti = array(':' => ':1', ']' => ':2', '[' => ':3', '"' => ':4');
    return
        strtr($v, ($Oa ? array_flip($ti) : $ti));
}

function
min_version($Zi, $Be = "", $h = null)
{
    global $g;
    if (!$h) $h = $g;
    $lh = $h->server_info;
    if ($Be && preg_match('~([\d.]+)-MariaDB~', $lh, $B)) {
        $lh = $B[1];
        $Zi = $Be;
    }
    return (version_compare($lh, $Zi) >= 0);
}

function
charset($g)
{
    return (min_version("5.5.3", 0, $g) ? "utf8mb4" : "utf8");
}

function
script($wh, $si = "\n")
{
    return "<script" . nonce() . ">$wh</script>$si";
}

function
script_src($Mi)
{
    return "<script src='" . h($Mi) . "'" . nonce() . "></script>\n";
}

function
nonce()
{
    return ' nonce="' . get_nonce() . '"';
}

function
target_blank()
{
    return ' target="_blank" rel="noreferrer noopener"';
}

function
h($P)
{
    return
        str_replace("\0", "&#0;", htmlspecialchars($P, ENT_QUOTES, 'utf-8'));
}

function
nl_br($P)
{
    return
        str_replace("\n", "<br>", $P);
}

function
checkbox($C, $Y, $fb, $ke = "", $sf = "", $kb = "", $le = "")
{
    $I = "<input type='checkbox' name='$C' value='" . h($Y) . "'" . ($fb ? " checked" : "") . ($le ? " aria-labelledby='$le'" : "") . ">" . ($sf ? script("qsl('input').onclick = function () { $sf };", "") : "");
    return ($ke != "" || $kb ? "<label" . ($kb ? " class='$kb'" : "") . ">$I" . h($ke) . "</label>" : $I);
}

function
optionlist($yf, $fh = null, $Ri = false)
{
    $I = "";
    foreach ($yf
             as $de => $W) {
        $zf = array($de => $W);
        if (is_array($W)) {
            $I .= '<optgroup label="' . h($de) . '">';
            $zf = $W;
        }
        foreach ($zf
                 as $z => $X) $I .= '<option' . ($Ri || is_string($z) ? ' value="' . h($z) . '"' : '') . (($Ri || is_string($z) ? (string)$z : $X) === $fh ? ' selected' : '') . '>' . h($X);
        if (is_array($W)) $I .= '</optgroup>';
    }
    return $I;
}

function
html_select($C, $yf, $Y = "", $rf = true, $le = "")
{
    if ($rf) return "<select name='" . h($C) . "'" . ($le ? " aria-labelledby='$le'" : "") . ">" . optionlist($yf, $Y) . "</select>" . (is_string($rf) ? script("qsl('select').onchange = function () { $rf };", "") : "");
    $I = "";
    foreach ($yf
             as $z => $X) $I .= "<label><input type='radio' name='" . h($C) . "' value='" . h($z) . "'" . ($z == $Y ? " checked" : "") . ">" . h($X) . "</label>";
    return $I;
}

function
select_input($Ja, $yf, $Y = "", $rf = "", $cg = "")
{
    $Xh = ($yf ? "select" : "input");
    return "<$Xh$Ja" . ($yf ? "><option value=''>$cg" . optionlist($yf, $Y, true) . "</select>" : " size='10' value='" . h($Y) . "' placeholder='$cg'>") . ($rf ? script("qsl('$Xh').onchange = $rf;", "") : "");
}

function
confirm($Le = "", $gh = "qsl('input')")
{
    return
        script("$gh.onclick = function () { return confirm('" . ($Le ? js_escape($Le) : 'Are you sure?') . "'); };", "");
}

function
print_fieldset($u, $se, $cj = false)
{
    echo "<fieldset><legend>", "<a href='#fieldset-$u'>$se</a>", script("qsl('a').onclick = partial(toggle, 'fieldset-$u');", ""), "</legend>", "<div id='fieldset-$u'" . ($cj ? "" : " class='hidden'") . ">\n";
}

function
bold($Wa, $kb = "")
{
    return ($Wa ? " class='active $kb'" : ($kb ? " class='$kb'" : ""));
}

function
odd($I = ' class="odd"')
{
    static $t = 0;
    if (!$I) $t = -1;
    return ($t++ % 2 ? $I : '');
}

function
js_escape($P)
{
    return
        addcslashes($P, "\r\n'\\/");
}

function
json_row($z, $X = null)
{
    static $Wc = true;
    if ($Wc) echo "{";
    if ($z != "") {
        echo ($Wc ? "" : ",") . "\n\t\"" . addcslashes($z, "\r\n\t\"\\/") . '": ' . ($X !== null ? '"' . addcslashes($X, "\r\n\"\\/") . '"' : 'null');
        $Wc = false;
    } else {
        echo "\n}\n";
        $Wc = true;
    }
}

function
ini_bool($Qd)
{
    $X = ini_get($Qd);
    return (preg_match('~^(on|true|yes)$~i', $X) || (int)$X);
}

function
sid()
{
    static $I;
    if ($I === null) $I = (SID && !($_COOKIE && ini_bool("session.use_cookies")));
    return $I;
}

function
set_password($Yi, $N, $V, $F)
{
    $_SESSION["pwds"][$Yi][$N][$V] = ($_COOKIE["adminer_key"] && is_string($F) ? array(encrypt_string($F, $_COOKIE["adminer_key"])) : $F);
}

function
get_password()
{
    $I = get_session("pwds");
    if (is_array($I)) $I = ($_COOKIE["adminer_key"] ? decrypt_string($I[0], $_COOKIE["adminer_key"]) : false);
    return $I;
}

function
q($P)
{
    global $g;
    return $g->quote($P);
}

function
get_vals($G, $e = 0)
{
    global $g;
    $I = array();
    $H = $g->query($G);
    if (is_object($H)) {
        while ($J = $H->fetch_row()) $I[] = $J[$e];
    }
    return $I;
}

function
get_key_vals($G, $h = null, $oh = true)
{
    global $g;
    if (!is_object($h)) $h = $g;
    $I = array();
    $H = $h->query($G);
    if (is_object($H)) {
        while ($J = $H->fetch_row()) {
            if ($oh) $I[$J[0]] = $J[1]; else$I[] = $J[0];
        }
    }
    return $I;
}

function
get_rows($G, $h = null, $o = "<p class='error'>")
{
    global $g;
    $xb = (is_object($h) ? $h : $g);
    $I = array();
    $H = $xb->query($G);
    if (is_object($H)) {
        while ($J = $H->fetch_assoc()) $I[] = $J;
    } elseif (!$H && !is_object($h) && $o && defined("PAGE_HEADER")) echo $o . error() . "\n";
    return $I;
}

function
unique_array($J, $x)
{
    foreach ($x
             as $w) {
        if (preg_match("~PRIMARY|UNIQUE~", $w["type"])) {
            $I = array();
            foreach ($w["columns"] as $z) {
                if (!isset($J[$z])) continue
                2;
                $I[$z] = $J[$z];
            }
            return $I;
        }
    }
}

function
escape_key($z)
{
    if (preg_match('(^([\w(]+)(' . str_replace("_", ".*", preg_quote(idf_escape("_"))) . ')([ \w)]+)$)', $z, $B)) return $B[1] . idf_escape(idf_unescape($B[2])) . $B[3];
    return
        idf_escape($z);
}

function
where($Z, $q = array())
{
    global $g, $y;
    $I = array();
    foreach ((array)$Z["where"] as $z => $X) {
        $z = bracket_escape($z, 1);
        $e = escape_key($z);
        $I[] = $e . ($y == "sql" && is_numeric($X) && preg_match('~\.~', $X) ? " LIKE " . q($X) : ($y == "mssql" ? " LIKE " . q(preg_replace('~[_%[]~', '[\0]', $X)) : " = " . unconvert_field($q[$z], q($X))));
        if ($y == "sql" && preg_match('~char|text~', $q[$z]["type"]) && preg_match("~[^ -@]~", $X)) $I[] = "$e = " . q($X) . " COLLATE " . charset($g) . "_bin";
    }
    foreach ((array)$Z["null"] as $z) $I[] = escape_key($z) . " IS NULL";
    return
        implode(" AND ", $I);
}

function
where_check($X, $q = array())
{
    parse_str($X, $db);
    remove_slashes(array(&$db));
    return
        where($db, $q);
}

function
where_link($t, $e, $Y, $uf = "=")
{
    return "&where%5B$t%5D%5Bcol%5D=" . urlencode($e) . "&where%5B$t%5D%5Bop%5D=" . urlencode(($Y !== null ? $uf : "IS NULL")) . "&where%5B$t%5D%5Bval%5D=" . urlencode($Y);
}

function
convert_fields($f, $q, $L = array())
{
    $I = "";
    foreach ($f
             as $z => $X) {
        if ($L && !in_array(idf_escape($z), $L)) continue;
        $Ga = convert_field($q[$z]);
        if ($Ga) $I .= ", $Ga AS " . idf_escape($z);
    }
    return $I;
}

function
cookie($C, $Y, $ve = 2592000)
{
    global $ba;
    return
        header("Set-Cookie: $C=" . urlencode($Y) . ($ve ? "; expires=" . gmdate("D, d M Y H:i:s", time() + $ve) . " GMT" : "") . "; path=" . preg_replace('~\?.*~', '', $_SERVER["REQUEST_URI"]) . ($ba ? "; secure" : "") . "; HttpOnly; SameSite=lax", false);
}

function
restart_session()
{
    if (!ini_bool("session.use_cookies")) session_start();
}

function
stop_session($bd = false)
{
    $Qi = ini_bool("session.use_cookies");
    if (!$Qi || $bd) {
        session_write_close();
        if ($Qi && @ini_set("session.use_cookies", false) === false) session_start();
    }
}

function&get_session($z)
{
    return $_SESSION[$z][DRIVER][SERVER][$_GET["username"]];
}

function
set_session($z, $X)
{
    $_SESSION[$z][DRIVER][SERVER][$_GET["username"]] = $X;
}

function
auth_url($Yi, $N, $V, $m = null)
{
    global $ec;
    preg_match('~([^?]*)\??(.*)~', remove_from_uri(implode("|", array_keys($ec)) . "|username|" . ($m !== null ? "db|" : "") . session_name()), $B);
    return "$B[1]?" . (sid() ? SID . "&" : "") . ($Yi != "server" || $N != "" ? urlencode($Yi) . "=" . urlencode($N) . "&" : "") . "username=" . urlencode($V) . ($m != "" ? "&db=" . urlencode($m) : "") . ($B[2] ? "&$B[2]" : "");
}

function
is_ajax()
{
    return ($_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest");
}

function
redirect($xe, $Le = null)
{
    if ($Le !== null) {
        restart_session();
        $_SESSION["messages"][preg_replace('~^[^?]*~', '', ($xe !== null ? $xe : $_SERVER["REQUEST_URI"]))][] = $Le;
    }
    if ($xe !== null) {
        if ($xe == "") $xe = ".";
        header("Location: $xe");
        exit;
    }
}

function
query_redirect($G, $xe, $Le, $Bg = true, $Cc = true, $Nc = false, $fi = "")
{
    global $g, $o, $b;
    if ($Cc) {
        $Dh = microtime(true);
        $Nc = !$g->query($G);
        $fi = format_time($Dh);
    }
    $zh = "";
    if ($G) $zh = $b->messageQuery($G, $fi, $Nc);
    if ($Nc) {
        $o = error() . $zh . script("messagesPrint();");
        return
            false;
    }
    if ($Bg) redirect($xe, $Le . $zh);
    return
        true;
}

function
queries($G)
{
    global $g;
    static $vg = array();
    static $Dh;
    if (!$Dh) $Dh = microtime(true);
    if ($G === null) return
        array(implode("\n", $vg), format_time($Dh));
    $vg[] = (preg_match('~;$~', $G) ? "DELIMITER ;;\n$G;\nDELIMITER " : $G) . ";";
    return $g->query($G);
}

function
apply_queries($G, $S, $zc = 'table')
{
    foreach ($S
             as $Q) {
        if (!queries("$G " . $zc($Q))) return
            false;
    }
    return
        true;
}

function
queries_redirect($xe, $Le, $Bg)
{
    list($vg, $fi) = queries(null);
    return
        query_redirect($vg, $xe, $Le, $Bg, false, !$Bg, $fi);
}

function
format_time($Dh)
{
    return
        sprintf('%.3f s', max(0, microtime(true) - $Dh));
}

function
remove_from_uri($Nf = "")
{
    return
        substr(preg_replace("~(?<=[?&])($Nf" . (SID ? "" : "|" . session_name()) . ")=[^&]*&~", '', "$_SERVER[REQUEST_URI]&"), 0, -1);
}

function
pagination($E, $Jb)
{
    return " " . ($E == $Jb ? $E + 1 : '<a href="' . h(remove_from_uri("page") . ($E ? "&page=$E" . ($_GET["next"] ? "&next=" . urlencode($_GET["next"]) : "") : "")) . '">' . ($E + 1) . "</a>");
}

function
get_file($z, $Rb = false)
{
    $Tc = $_FILES[$z];
    if (!$Tc) return
        null;
    foreach ($Tc
             as $z => $X) $Tc[$z] = (array)$X;
    $I = '';
    foreach ($Tc["error"] as $z => $o) {
        if ($o) return $o;
        $C = $Tc["name"][$z];
        $ni = $Tc["tmp_name"][$z];
        $_b = file_get_contents($Rb && preg_match('~\.gz$~', $C) ? "compress.zlib://$ni" : $ni);
        if ($Rb) {
            $Dh = substr($_b, 0, 3);
            if (function_exists("iconv") && preg_match("~^\xFE\xFF|^\xFF\xFE~", $Dh, $Hg)) $_b = iconv("utf-16", "utf-8", $_b); elseif ($Dh == "\xEF\xBB\xBF") $_b = substr($_b, 3);
            $I .= $_b . "\n\n";
        } else$I .= $_b;
    }
    return $I;
}

function
upload_error($o)
{
    $Ie = ($o == UPLOAD_ERR_INI_SIZE ? ini_get("upload_max_filesize") : 0);
    return ($o ? 'Unable to upload a file.' . ($Ie ? " " . sprintf('Maximum allowed file size is %sB.', $Ie) : "") : 'File does not exist.');
}

function
repeat_pattern($ag, $te)
{
    return
        str_repeat("$ag{0,65535}", $te / 65535) . "$ag{0," . ($te % 65535) . "}";
}

function
is_utf8($X)
{
    return (preg_match('~~u', $X) && !preg_match('~[\0-\x8\xB\xC\xE-\x1F]~', $X));
}

function
shorten_utf8($P, $te = 80, $Lh = "")
{
    if (!preg_match("(^(" . repeat_pattern("[\t\r\n -\x{10FFFF}]", $te) . ")($)?)u", $P, $B)) preg_match("(^(" . repeat_pattern("[\t\r\n -~]", $te) . ")($)?)", $P, $B);
    return
        h($B[1]) . $Lh . (isset($B[2]) ? "" : "<i>…</i>");
}

function
format_number($X)
{
    return
        strtr(number_format($X, 0, ".", ','), preg_split('~~u', '0123456789', -1, PREG_SPLIT_NO_EMPTY));
}

function
friendly_url($X)
{
    return
        preg_replace('~[^a-z0-9_]~i', '-', $X);
}

function
hidden_fields($qg, $Fd = array())
{
    $I = false;
    while (list($z, $X) = each($qg)) {
        if (!in_array($z, $Fd)) {
            if (is_array($X)) {
                foreach ($X
                         as $de => $W) $qg[$z . "[$de]"] = $W;
            } else {
                $I = true;
                echo '<input type="hidden" name="' . h($z) . '" value="' . h($X) . '">';
            }
        }
    }
    return $I;
}

function
hidden_fields_get()
{
    echo(sid() ? '<input type="hidden" name="' . session_name() . '" value="' . h(session_id()) . '">' : ''), (SERVER !== null ? '<input type="hidden" name="' . DRIVER . '" value="' . h(SERVER) . '">' : ""), '<input type="hidden" name="username" value="' . h($_GET["username"]) . '">';
}

function
table_status1($Q, $Oc = false)
{
    $I = table_status($Q, $Oc);
    return ($I ? $I : array("Name" => $Q));
}

function
column_foreign_keys($Q)
{
    global $b;
    $I = array();
    foreach ($b->foreignKeys($Q) as $r) {
        foreach ($r["source"] as $X) $I[$X][] = $r;
    }
    return $I;
}

function
enum_input($T, $Ja, $p, $Y, $tc = null)
{
    global $b;
    preg_match_all("~'((?:[^']|'')*)'~", $p["length"], $De);
    $I = ($tc !== null ? "<label><input type='$T'$Ja value='$tc'" . ((is_array($Y) ? in_array($tc, $Y) : $Y === 0) ? " checked" : "") . "><i>" . 'empty' . "</i></label>" : "");
    foreach ($De[1] as $t => $X) {
        $X = stripcslashes(str_replace("''", "'", $X));
        $fb = (is_int($Y) ? $Y == $t + 1 : (is_array($Y) ? in_array($t + 1, $Y) : $Y === $X));
        $I .= " <label><input type='$T'$Ja value='" . ($t + 1) . "'" . ($fb ? ' checked' : '') . '>' . h($b->editVal($X, $p)) . '</label>';
    }
    return $I;
}

function
input($p, $Y, $s)
{
    global $U, $b, $y;
    $C = h(bracket_escape($p["field"]));
    echo "<td class='function'>";
    if (is_array($Y) && !$s) {
        $Ea = array($Y);
        if (version_compare(PHP_VERSION, 5.4) >= 0) $Ea[] = JSON_PRETTY_PRINT;
        $Y = call_user_func_array('json_encode', $Ea);
        $s = "json";
    }
    $Lg = ($y == "mssql" && $p["auto_increment"]);
    if ($Lg && !$_POST["save"]) $s = null;
    $kd = (isset($_GET["select"]) || $Lg ? array("orig" => 'original') : array()) + $b->editFunctions($p);
    $Ja = " name='fields[$C]'";
    if ($p["type"] == "enum") echo
        h($kd[""]) . "<td>" . $b->editInput($_GET["edit"], $p, $Ja, $Y); else {
        $ud = (in_array($s, $kd) || isset($kd[$s]));
        echo (count($kd) > 1 ? "<select name='function[$C]'>" . optionlist($kd, $s === null || $ud ? $s : "") . "</select>" . on_help("getTarget(event).value.replace(/^SQL\$/, '')", 1) . script("qsl('select').onchange = functionChange;", "") : h(reset($kd))) . '<td>';
        $Sd = $b->editInput($_GET["edit"], $p, $Ja, $Y);
        if ($Sd != "") echo $Sd; elseif (preg_match('~bool~', $p["type"])) echo "<input type='hidden'$Ja value='0'>" . "<input type='checkbox'" . (preg_match('~^(1|t|true|y|yes|on)$~i', $Y) ? " checked='checked'" : "") . "$Ja value='1'>";
        elseif ($p["type"] == "set") {
            preg_match_all("~'((?:[^']|'')*)'~", $p["length"], $De);
            foreach ($De[1] as $t => $X) {
                $X = stripcslashes(str_replace("''", "'", $X));
                $fb = (is_int($Y) ? ($Y >> $t) & 1 : in_array($X, explode(",", $Y), true));
                echo " <label><input type='checkbox' name='fields[$C][$t]' value='" . (1 << $t) . "'" . ($fb ? ' checked' : '') . ">" . h($b->editVal($X, $p)) . '</label>';
            }
        } elseif (preg_match('~blob|bytea|raw|file~', $p["type"]) && ini_bool("file_uploads")) echo "<input type='file' name='fields-$C'>";
        elseif (($di = preg_match('~text|lob|memo~i', $p["type"])) || preg_match("~\n~", $Y)) {
            if ($di && $y != "sqlite") $Ja .= " cols='50' rows='12'"; else {
                $K = min(12, substr_count($Y, "\n") + 1);
                $Ja .= " cols='30' rows='$K'" . ($K == 1 ? " style='height: 1.2em;'" : "");
            }
            echo "<textarea$Ja>" . h($Y) . '</textarea>';
        } elseif ($s == "json" || preg_match('~^jsonb?$~', $p["type"])) echo "<textarea$Ja cols='50' rows='12' class='jush-js'>" . h($Y) . '</textarea>';
        else {
            $Ke = (!preg_match('~int~', $p["type"]) && preg_match('~^(\d+)(,(\d+))?$~', $p["length"], $B) ? ((preg_match("~binary~", $p["type"]) ? 2 : 1) * $B[1] + ($B[3] ? 1 : 0) + ($B[2] && !$p["unsigned"] ? 1 : 0)) : ($U[$p["type"]] ? $U[$p["type"]] + ($p["unsigned"] ? 0 : 1) : 0));
            if ($y == 'sql' && min_version(5.6) && preg_match('~time~', $p["type"])) $Ke += 7;
            echo "<input" . ((!$ud || $s === "") && preg_match('~(?<!o)int(?!er)~', $p["type"]) && !preg_match('~\[\]~', $p["full_type"]) ? " type='number'" : "") . " value='" . h($Y) . "'" . ($Ke ? " data-maxlength='$Ke'" : "") . (preg_match('~char|binary~', $p["type"]) && $Ke > 20 ? " size='40'" : "") . "$Ja>";
        }
        echo $b->editHint($_GET["edit"], $p, $Y);
        $Wc = 0;
        foreach ($kd
                 as $z => $X) {
            if ($z === "" || !$X) break;
            $Wc++;
        }
        if ($Wc) echo
        script("mixin(qsl('td'), {onchange: partial(skipOriginal, $Wc), oninput: function () { this.onchange(); }});");
    }
}

function
process_input($p)
{
    global $b, $n;
    $v = bracket_escape($p["field"]);
    $s = $_POST["function"][$v];
    $Y = $_POST["fields"][$v];
    if ($p["type"] == "enum") {
        if ($Y == -1) return
            false;
        if ($Y == "") return "NULL";
        return +$Y;
    }
    if ($p["auto_increment"] && $Y == "") return
        null;
    if ($s == "orig") return (preg_match('~^CURRENT_TIMESTAMP~i', $p["on_update"]) ? idf_escape($p["field"]) : false);
    if ($s == "NULL") return "NULL";
    if ($p["type"] == "set") return
        array_sum((array)$Y);
    if ($s == "json") {
        $s = "";
        $Y = json_decode($Y, true);
        if (!is_array($Y)) return
            false;
        return $Y;
    }
    if (preg_match('~blob|bytea|raw|file~', $p["type"]) && ini_bool("file_uploads")) {
        $Tc = get_file("fields-$v");
        if (!is_string($Tc)) return
            false;
        return $n->quoteBinary($Tc);
    }
    return $b->processInput($p, $Y, $s);
}

function
fields_from_edit()
{
    global $n;
    $I = array();
    foreach ((array)$_POST["field_keys"] as $z => $X) {
        if ($X != "") {
            $X = bracket_escape($X);
            $_POST["function"][$X] = $_POST["field_funs"][$z];
            $_POST["fields"][$X] = $_POST["field_vals"][$z];
        }
    }
    foreach ((array)$_POST["fields"] as $z => $X) {
        $C = bracket_escape($z, 1);
        $I[$C] = array("field" => $C, "privileges" => array("insert" => 1, "update" => 1), "null" => 1, "auto_increment" => ($z == $n->primary),);
    }
    return $I;
}

function
search_tables()
{
    global $b, $g;
    $_GET["where"][0]["val"] = $_POST["query"];
    $ih = "<ul>\n";
    foreach (table_status('', true) as $Q => $R) {
        $C = $b->tableName($R);
        if (isset($R["Engine"]) && $C != "" && (!$_POST["tables"] || in_array($Q, $_POST["tables"]))) {
            $H = $g->query("SELECT" . limit("1 FROM " . table($Q), " WHERE " . implode(" AND ", $b->selectSearchProcess(fields($Q), array())), 1));
            if (!$H || $H->fetch_row()) {
                $mg = "<a href='" . h(ME . "select=" . urlencode($Q) . "&where[0][op]=" . urlencode($_GET["where"][0]["op"]) . "&where[0][val]=" . urlencode($_GET["where"][0]["val"])) . "'>$C</a>";
                echo "$ih<li>" . ($H ? $mg : "<p class='error'>$mg: " . error()) . "\n";
                $ih = "";
            }
        }
    }
    echo ($ih ? "<p class='message'>" . 'No tables.' : "</ul>") . "\n";
}

function
dump_headers($Cd, $Ue = false)
{
    global $b;
    $I = $b->dumpHeaders($Cd, $Ue);
    $Kf = $_POST["output"];
    if ($Kf != "text") header("Content-Disposition: attachment; filename=" . $b->dumpFilename($Cd) . ".$I" . ($Kf != "file" && !preg_match('~[^0-9a-z]~', $Kf) ? ".$Kf" : ""));
    session_write_close();
    ob_flush();
    flush();
    return $I;
}

function
dump_csv($J)
{
    foreach ($J
             as $z => $X) {
        if (preg_match("~[\"\n,;\t]~", $X) || $X === "") $J[$z] = '"' . str_replace('"', '""', $X) . '"';
    }
    echo
        implode(($_POST["format"] == "csv" ? "," : ($_POST["format"] == "tsv" ? "\t" : ";")), $J) . "\r\n";
}

function
apply_sql_function($s, $e)
{
    return ($s ? ($s == "unixepoch" ? "DATETIME($e, '$s')" : ($s == "count distinct" ? "COUNT(DISTINCT " : strtoupper("$s(")) . "$e)") : $e);
}

function
get_temp_dir()
{
    $I = ini_get("upload_tmp_dir");
    if (!$I) {
        if (function_exists('sys_get_temp_dir')) $I = sys_get_temp_dir(); else {
            $Uc = @tempnam("", "");
            if (!$Uc) return
                false;
            $I = dirname($Uc);
            unlink($Uc);
        }
    }
    return $I;
}

function
file_open_lock($Uc)
{
    $id = @fopen($Uc, "r+");
    if (!$id) {
        $id = @fopen($Uc, "w");
        if (!$id) return;
        chmod($Uc, 0660);
    }
    flock($id, LOCK_EX);
    return $id;
}

function
file_write_unlock($id, $Lb)
{
    rewind($id);
    fwrite($id, $Lb);
    ftruncate($id, strlen($Lb));
    flock($id, LOCK_UN);
    fclose($id);
}

function
password_file($i)
{
    $Uc = get_temp_dir() . "/adminer.key";
    $I = @file_get_contents($Uc);
    if ($I || !$i) return $I;
    $id = @fopen($Uc, "w");
    if ($id) {
        chmod($Uc, 0660);
        $I = rand_string();
        fwrite($id, $I);
        fclose($id);
    }
    return $I;
}

function
rand_string()
{
    return
        md5(uniqid(mt_rand(), true));
}

function
select_value($X, $A, $p, $ei)
{
    global $b;
    if (is_array($X)) {
        $I = "";
        foreach ($X
                 as $de => $W) $I .= "<tr>" . ($X != array_values($X) ? "<th>" . h($de) : "") . "<td>" . select_value($W, $A, $p, $ei);
        return "<table cellspacing='0'>$I</table>";
    }
    if (!$A) $A = $b->selectLink($X, $p);
    if ($A === null) {
        if (is_mail($X)) $A = "mailto:$X";
        if (is_url($X)) $A = $X;
    }
    $I = $b->editVal($X, $p);
    if ($I !== null) {
        if (!is_utf8($I)) $I = "\0"; elseif ($ei != "" && is_shortable($p)) $I = shorten_utf8($I, max(0, +$ei));
        else$I = h($I);
    }
    return $b->selectVal($I, $A, $p, $X);
}

function
is_mail($qc)
{
    $Ha = '[-a-z0-9!#$%&\'*+/=?^_`{|}~]';
    $dc = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';
    $ag = "$Ha+(\\.$Ha+)*@($dc?\\.)+$dc";
    return
        is_string($qc) && preg_match("(^$ag(,\\s*$ag)*\$)i", $qc);
}

function
is_url($P)
{
    $dc = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';
    return
        preg_match("~^(https?)://($dc?\\.)+$dc(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i", $P);
}

function
is_shortable($p)
{
    return
        preg_match('~char|text|json|lob|geometry|point|linestring|polygon|string|bytea~', $p["type"]);
}

function
count_rows($Q, $Z, $Yd, $nd)
{
    global $y;
    $G = " FROM " . table($Q) . ($Z ? " WHERE " . implode(" AND ", $Z) : "");
    return ($Yd && ($y == "sql" || count($nd) == 1) ? "SELECT COUNT(DISTINCT " . implode(", ", $nd) . ")$G" : "SELECT COUNT(*)" . ($Yd ? " FROM (SELECT 1$G GROUP BY " . implode(", ", $nd) . ") x" : $G));
}

function
slow_query($G)
{
    global $b, $pi, $n;
    $m = $b->database();
    $gi = $b->queryTimeout();
    $th = $n->slowQuery($G, $gi);
    if (!$th && support("kill") && is_object($h = connect()) && ($m == "" || $h->select_db($m))) {
        $ie = $h->result(connection_id());
        echo '<script', nonce(), '>
var timeout = setTimeout(function () {
	ajax(\'', js_escape(ME), 'script=kill\', function () {
	}, \'kill=', $ie, '&token=', $pi, '\');
}, ', 1000 * $gi, ');
</script>
';
    } else$h = null;
    ob_flush();
    flush();
    $I = @get_key_vals(($th ? $th : $G), $h, false);
    if ($h) {
        echo
        script("clearTimeout(timeout);");
        ob_flush();
        flush();
    }
    return $I;
}

function
get_token()
{
    $yg = rand(1, 1e6);
    return ($yg ^ $_SESSION["token"]) . ":$yg";
}

function
verify_token()
{
    list($pi, $yg) = explode(":", $_POST["token"]);
    return ($yg ^ $_SESSION["token"]) == $pi;
}

function
lzw_decompress($Sa)
{
    $Zb = 256;
    $Ta = 8;
    $mb = array();
    $Ng = 0;
    $Og = 0;
    for ($t = 0; $t < strlen($Sa); $t++) {
        $Ng = ($Ng << 8) + ord($Sa[$t]);
        $Og += 8;
        if ($Og >= $Ta) {
            $Og -= $Ta;
            $mb[] = $Ng >> $Og;
            $Ng &= (1 << $Og) - 1;
            $Zb++;
            if ($Zb >> $Ta) $Ta++;
        }
    }
    $Yb = range("\0", "\xFF");
    $I = "";
    foreach ($mb
             as $t => $lb) {
        $pc = $Yb[$lb];
        if (!isset($pc)) $pc = $nj . $nj[0];
        $I .= $pc;
        if ($t) $Yb[] = $nj . $pc[0];
        $nj = $pc;
    }
    return $I;
}

function
on_help($sb, $qh = 0)
{
    return
        script("mixin(qsl('select, input'), {onmouseover: function (event) { helpMouseover.call(this, event, $sb, $qh) }, onmouseout: helpMouseout});", "");
}

function
edit_form($a, $q, $J, $Ki)
{
    global $b, $y, $pi, $o;
    $Qh = $b->tableName(table_status1($a, true));
    page_header(($Ki ? 'Edit' : 'Insert'), $o, array("select" => array($a, $Qh)), $Qh);
    if ($J === false) echo "<p class='error'>" . 'No rows.' . "\n";
    echo '<form action="" method="post" enctype="multipart/form-data" id="form">
';
    if (!$q) echo "<p class='error'>" . 'You have no privileges to update this table.' . "\n"; else {
        echo "<table cellspacing='0' class='layout'>" . script("qsl('table').onkeydown = editingKeydown;");
        foreach ($q
                 as $C => $p) {
            echo "<tr><th>" . $b->fieldName($p);
            $Sb = $_GET["set"][bracket_escape($C)];
            if ($Sb === null) {
                $Sb = $p["default"];
                if ($p["type"] == "bit" && preg_match("~^b'([01]*)'\$~", $Sb, $Hg)) $Sb = $Hg[1];
            }
            $Y = ($J !== null ? ($J[$C] != "" && $y == "sql" && preg_match("~enum|set~", $p["type"]) ? (is_array($J[$C]) ? array_sum($J[$C]) : +$J[$C]) : $J[$C]) : (!$Ki && $p["auto_increment"] ? "" : (isset($_GET["select"]) ? false : $Sb)));
            if (!$_POST["save"] && is_string($Y)) $Y = $b->editVal($Y, $p);
            $s = ($_POST["save"] ? (string)$_POST["function"][$C] : ($Ki && preg_match('~^CURRENT_TIMESTAMP~i', $p["on_update"]) ? "now" : ($Y === false ? null : ($Y !== null ? '' : 'NULL'))));
            if (preg_match("~time~", $p["type"]) && preg_match('~^CURRENT_TIMESTAMP~i', $Y)) {
                $Y = "";
                $s = "now";
            }
            input($p, $Y, $s);
            echo "\n";
        }
        if (!support("table")) echo "<tr>" . "<th><input name='field_keys[]'>" . script("qsl('input').oninput = fieldChange;") . "<td class='function'>" . html_select("field_funs[]", $b->editFunctions(array("null" => isset($_GET["select"])))) . "<td><input name='field_vals[]'>" . "\n";
        echo "</table>\n";
    }
    echo "<p>\n";
    if ($q) {
        echo "<input type='submit' value='" . 'Save' . "'>\n";
        if (!isset($_GET["select"])) {
            echo "<input type='submit' name='insert' value='" . ($Ki ? 'Save and continue edit' : 'Save and insert next') . "' title='Ctrl+Shift+Enter'>\n", ($Ki ? script("qsl('input').onclick = function () { return !ajaxForm(this.form, '" . 'Saving' . "…', this); };") : "");
        }
    }
    echo($Ki ? "<input type='submit' name='delete' value='" . 'Delete' . "'>" . confirm() . "\n" : ($_POST || !$q ? "" : script("focus(qsa('td', qs('#form'))[1].firstChild);")));
    if (isset($_GET["select"])) hidden_fields(array("check" => (array)$_POST["check"], "clone" => $_POST["clone"], "all" => $_POST["all"]));
    echo '<input type="hidden" name="referer" value="', h(isset($_POST["referer"]) ? $_POST["referer"] : $_SERVER["HTTP_REFERER"]), '">
<input type="hidden" name="save" value="1">
<input type="hidden" name="token" value="', $pi, '">
</form>
';
}

if (isset($_GET["file"])) {
    if ($_SERVER["HTTP_IF_MODIFIED_SINCE"]) {
        header("HTTP/1.1 304 Not Modified");
        exit;
    }
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + 365 * 24 * 60 * 60) . " GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: immutable");
    if ($_GET["file"] == "favicon.ico") {
        header("Content-Type: image/x-icon");
        echo
        lzw_decompress("\0\0\0` \0�\0\n @\0�C��\"\0`E�Q����?�tvM'�Jd�d\\�b0\0�\"��fӈ��s5����A�XPaJ�0���8�#R�T��z`�#.��c�X��Ȁ?�-\0�Im?�.�M��\0ȯ(̉��/(%�\0");
    } elseif ($_GET["file"] == "default.css") {
        header("Content-Type: text/css; charset=utf-8");
        echo
        lzw_decompress("\n1̇�ٌ�l7��B1�4vb0��fs���n2B�ѱ٘�n:�#(�b.\rDc)��a7E����l�ñ��i1̎s���-4��f�	��i7�����t4���y�Zf4��i�AT�VV��f:Ϧ,:1�Qݼ�b2`�#�>:7G�1���s��L�XD*bv<܌#�e@�:4�!fo���t:<��咾�o��\ni���',�a_�:�i�Bv�|N�4.5Nf�i�vp�h��l��֚�O����= �OFQ��k\$��i����d2T�p��6�����-�Z�����6����h:�a�,����2�#8А�#��6n����J��h�t�����4O42��ok��*r���@p@�!������?�6��r[��L���:2B�j�!Hb��P�=!1V�\"��0��\nS���D7��Dڛ�C!�!��Gʌ� �+�=tC�.C��:+��=�������%�c�1MR/�EȒ4���2�䱠�`�8(�ӹ[W��=�yS�b�=�-ܹBS+ɯ�����@pL4Yd��q�����6�3Ĭ��Ac܌�Ψ�k�[&>���Z�pkm]�u-c:���Nt�δpҝ��8�=�#��[.��ޯ�~���m�y�PP�|I֛���Q�9v[�Q��\n��r�'g�+��T�2��V��z�4��8��(	�Ey*#j�2]��R����)��[N�R\$�<>:�>\$;�>��\r���H��T�\nw�N �wأ��<��Gw����\\Y�_�Rt^�>�\r}��S\rz�4=�\nL�%J��\",Z�8����i�0u�?�����s3#�ى�:���㽖��E]x���s^8��K^��*0��w����~���:��i���v2w����^7���7�c��u+U%�{P�*4̼�LX./!��1C��qx!H��Fd��L���Ġ�`6��5��f��Ć�=H�l �V1��\0a2�;��6����_ه�\0&�Z�S�d)KE'��n��[X��\0ZɊ�F[P�ޘ@��!��Y�,`�\"ڷ��0Ee9yF>��9b����F5:���\0}Ĵ��(\$����37H��� M�A��6R��{Mq�7G��C�C�m2�(�Ct>[�-t�/&C�]�etG�̬4@r>���<�Sq�/���Q�hm���������L��#��K�|���6fKP�\r%t��V=\"�SH\$�} ��)w�,W\0F��u@�b�9�\rr�2�#�D��X���yOI�>��n��Ǣ%���'��_��t\rτz�\\1�hl�]Q5Mp6k���qh�\$�H~�|��!*4����`S���S t�PP\\g��7�\n-�:袪p����l�B���7Өc�(wO0\\:��w���p4���{T��jO�6HÊ�r���q\n��%%�y']\$��a�Z�.fc�q*-�FW��k��z���j���lg�:�\$\"�N�\r#�d�Â���sc�̠��\"j�\r�����Ւ�Ph�1/��DA)���[�kn�p76�Y��R{�M�P���@\n-�a�6��[�zJH,�dl�B�h�o�����+�#Dr^�^��e��E��� ĜaP���JG�z��t�2�X�����V�����ȳ��B_%K=E��b弾�§kU(.!ܮ8����I.@�K�xn���:�P�32��m�H		C*�:v�T�\nR�����0u�����ҧ]�����P/�JQd�{L�޳:Y��2b��T ��3�4���c�V=���L4��r�!�B�Y�6��MeL������i�o�9< G��ƕЙMhm^�U�N����Tr5HiM�/�n�흳T��[-<__�3/Xr(<���������uҖGNX20�\r\$^��:'9�O��;�k����f��N'a����b�,�V��1��HI!%6@��\$�EGڜ�1�(mU��rս���`��iN+Ü�)���0l��f0��[U��V��-:I^��\$�s�b\re��ug�h�~9�߈�b�����f�+0�� hXrݬ�!\$�e,�w+����3��_�A�k��\nk�r�ʛcuWdY�\\�={.�č���g��p8�t\rRZ�v�J:�>��Y|+�@����C�t\r��jt��6��%�?��ǎ�>�/�����9F`ו��v~K�����R�W��z��lm�wL�9Y�*q�x�z��Se�ݛ����~�D�����x���ɟi7�2���Oݻ��_{��53��t���_��z�3�d)�C��\$?KӪP�%��T&��&\0P�NA�^�~���p� �Ϝ���\r\$�����b*+D6궦ψ��J\$(�ol��h&��KBS>���;z��x�oz>��o�Z�\nʋ[�v���Ȝ��2�OxِV�0f�����2Bl�bk�6Zk�hXcd�0*�KT�H=��π�p0�lV����\r���n�m��)(�(�:#����E��:C�C���\r�G\ré0��i����:`Z1Q\n:��\r\0���q���:`�-�M#}1;����q�#|�S���hl�D�\0fiDp�L��``����0y��1���\r�=�MQ\\��%oq��\0��1�21�1�� ���ќbi:��\r�/Ѣ� `)��0��@���I1�N�C�����O��Z��1���q1 ����,�\rdI�Ǧv�j�1 t�B���⁒0:�0��1�A2V���0���%�fi3!&Q�Rc%�q&w%��\r��V�#���Qw`�% ���m*r��y&i�+r{*��(rg(�#(2�(��)R@i�-�� ���1\"\0��R���.e.r��,�ry(2�C��b�!Bޏ3%ҵ,R�1��&��t��b�a\rL��-3�����\0��Bp�1�94�O'R�3*��=\$�[�^iI;/3i�5�&�}17�# ѹ8��\"�7��8�9*�23�!�!1\\\0�8��rk9�;S�23��ړ*�:q]5S<��#3�83�#e�=�>~9S螳�r�)��T*a�@і�bes���:-���*;,�ؙ3!i���LҲ�#1 �+n� �*��@�3i7�1���_�F�S;3�F�\rA��3�>�x:� \r�0��@�-�/��w��7��S�J3� �.F�\$O�B���%4�+t�'g�Lq\rJt�J��M2\r��7��T@���)ⓣd��2�P>ΰ��Fi಴�\nr\0��b�k(�D���KQ����1�\"2t����P�\r��,\$KCt�5��#��)��P#Pi.�U2�C�~�\"�");
    } elseif ($_GET["file"] == "functions.js") {
        header("Content-Type: text/javascript; charset=utf-8");
        echo
        lzw_decompress("f:��gCI��\n8��3)��7���81��x:\nOg#)��r7\n\"��`�|2�gSi�H)N�S��\r��\"0��@�)�`(\$s6O!��V/=��' T4�=��iS��6IO��er�x�9�*ź��n3�\rщv�C��`���2G%�Y�����1��f���Ȃl��1�\ny�*pC\r\$�n�T��3=\\�r9O\"�	��l<�\r�\\��I,�s\nA��eh+M�!�q0��f�`(�N{c��+w���Y��p٧3�3��+I��j�����k��n�q���zi#^r�����3���[��o;��(��6�#�Ґ��\":cz>ߣC2v�CX�<�P��c*5\n���/�P97�|F��c0�����!���!���!��\nZ%�ć#CH�!��r8�\$���,�Rܔ2���^0��@�2��(�88P/��݄�\\�\$La\\�;c�H��HX���\nʃt���8A<�sZ�*�;I��3��@�2<���!A8G<�j�-K�({*\r��a1���N4Tc\"\\�!=1^���M9O�:�;j��\r�X��L#H�7�#Tݪ/-���p�;�B \n�2!���t]apΎ��\0R�C�v�M�I,\r���\0Hv��?kT�4����uٱ�;&���+&���\r�X���bu4ݡi88�2B�/⃖4���N8A�A)52������2��s�8�5���p�WC@�:�t�㾴�e��h\"#8_��cp^��I]OH��:zd�3g�(���Ök��\\6����2�ږ��i��7���]\r�xO�n�p�<��p�Q�U�n��|@���#G3��8bA��6�2�67%#�\\8\r��2�c\r�ݟk��.(�	��-�J;��� ��L�� ���W��㧓ѥɤ����n��ҧ���M��9ZНs]�z����y^[��4-�U\0ta��62^��.`���.C�j�[ᄠ% Q\0`d�M8�����\$O0`4���\n\0a\rA�<�@����\r!�:�BA�9�?h>�Ǻ��~̌�6Ȉh�=�-�A7X��և\\�\r��Q<蚧q�'!XΓ2�T �!�D\r��,K�\"�%�H�qR\r�̠��C =�������<c�\n#<�5�M� �E��y�������o\"�cJKL2�&��eR��W�AΐTw�ё;�J���\\`)5��ޜB�qhT3��R	�'\r+\":�����.��ZM'|�et:3%L��#f!�h�׀e����+ļ�N�	��_�CX��G�1��i-ãz�\$�oK@O@T�=&�0�\$	�DA�����D�SJ�x9ׁFȈml��p�Gխ�T�6Rf�@�a�\rs�R�Fgih]��f�.�7+�<nhh�* �SH	P]� :Ғ��a\"�����2�&R�)�B�Pʙ�H/��f {r|�0^�hCA�0�@�M���2�B�@��z�U���O���Cpp��\\�L�%�𛄒y��odå���p3���7E����A\\���K��Xn��i.�Z�� ���s��G�m^�tI�Y�J��ٱ�G1��R��D��c���6�tMih��9��9g��q�RL��Mj-TQ�6i�G_!�.�h�v��cN�����^��0w@n|���V�ܫ�AЭ��3�[��]�	s7�G�P@ :�1т�b� ��ݟ���w�(i��:��z\\��;���A�PU T^�]9�`UX+U��Q+��b���*ϔs������[�ۉxk�F*�ݧ_w.��6~�b��mK�sI�MK�}�ҥ���eHɲ�d�*md�l�Q��eH�2�ԍL���a҂�=��s�P�aM\"ap��:<��GB�\r2Ytx&L}}��A�ԱN�GЬza��D4�t�4Q�vS�ùS\r�;U��������~�pB��{���,���O��t;�J��ZC,&Y�:Y\"�#�����t:\n�h8r����n���h>��>Z��`&�a�pY+�x�U��A�<?�PxWա�W�	i��.�\r`�\$,���Ҿ��V�]�Zr���H��5�f\\�-KƩ�v��Z��A��(�{3�o��l.��J��.�\\t2�;���2\0��>c+�|��*;-0�n��[�t@�ڕ��=cQ\n.z���wC&��@���F�����'cBS7_*rsѨ�?j�3@����!�.@7�s�]Ӫ�L�΁G��@��_�q���&u���t�\nՎ�L�E�T��}gG����w�o�(*�����A�-�����3�mk�����פ��t��S���(�d��A�~�x\n����k�ϣ:D��+�� g��h14 ��\n.��d꫖������AlY��j���jJ���PN+b� D�j������D��P���LQ`Of��@�}�(���6�^nB�4�`�e��\n��	�trp!�lV�'�}b�*�r%|\nr\r#���@w��-�T.Vv�8��\nmF�/�p��`�Y0�����P\r8�Y\r��ݤ�	�Q���%E�/@]\0��{@�Q���\0bR M\r��'|��%0SDr����f/����b:ܭ�����%߀�3H�x\0�l\0���	��W��%�\n�8\r\0}�D���1d#�x��.�jEoHrǢlb���%t�4�p���%�4���k�z2\r�`�W@�%\rJ�1��X���1�D6!��*��{4<E��k.m�4����\r\n�^i��� �!n��!2\$������(�f������k>����N��5\$���2T�,�LĂ� � Z@��*�`^P�P%5%�t�H�W��on���E#f���<�2@K:�o����Ϧ�-��2\\Wi+f�&��g&�n�L�'e�|����nK�2�rڶ�p�*.�n��������*�+�t�Bg* ��Q�1+)1h���^�`Q#�؎�n*h���v�B��\0\\F\n�W�r f\$�=4\$G4ed�b�:J^!�0��_���%2��6�.F���Һ�EQ�����dts\"�����B(�`�\r���c�R����V����X��:R�*2E*s�\$��+�:bXl��tb��-�S>��-�d�=��\$S�\$�2�ʁ7�j�\"[́\"��]�[6��SE_>�q.\$@z`�;�4�3ʼ�CS�*�[���{DO�ުCJj峚P�:'���ȕ QEӖ�`%r��7��G+hW4E*��#TuFj�\n�e�D�^�s��r.��Rk��z@��@���D�`C�V!C���\0��ۊ)3<��Q4@�3SP��ZB�5F�L�~G�5���:���5\$X���}ƞf���I���3S8�\0XԂtd�<\nbtN� Q�;\r��H��P�\0��&\n���\$V�\r:�\0]V5gV���D`�N1:�SS4Q�4�N��5u�5�`x	�<5_FH���}7��)�SV��Ğ#�|��< ռ�˰���\\��-�z2�\0�#�WJU6kv���#��\r�췐����U��i��_��^�UVJ|Y.��ɛ\0u,�������_UQD#�ZJu�Xt��_�&JO,Du`N\r5��`�}ZQM^m�P�G[��a�b�N䞮��re�\n��%�4��o_(�^�q@Y6t;I\nGSM�3��^SAYH�hB��5�fN?NjWU�J����֯Yֳke\"\\B1�؅0� �en���*<�O`S�L�\n��.g�5Zj�\0R\$�h��n�[�\\���r���,�4����cP�p�q@R�rw>�wCK��t��}5_uvh��`/����\$�J)�R�2Du73�d\r�;��w���H�I_\"4�r�����Ͽ+�&0>�_-eqeD��V��n��f�h��\"Z����Z�W�6\\L���ke&�~������i\$ϰ�Mr�i*�����\0�.Q,��8\r���\$׭K��Y� �io�e%t�2�\0�J��~��/I/.�e��n�~x!�8��|f�h�ۄ-H���&�/��o�����.K� �^j��t��>('L\r��HsK1�e�\0��\$&3�\0�in3� o�6�ж�����9�j������1�(b.�vC�ݎ8���:wi��\"�^w�Q����z�o~�/��Ғ���`Y2��D�V����/k�8��7Z�H����]2k2r���ϯh�=�T��]O&�\0�M\0�[8��Ȯ���8&L�Vm�v���j�ך�F��\\��	���&s��Q� \\\"�b��	��\rBs�Iw�	�Y��N �7�C/*����\n\n�H�[����*A���TE�VP.UZ(tz/}\n2��y�S���,#�3�i�~W@yCC\nKT��1\"@|�zC\$��_CZjzHB�LV�,K����O���P�@X���������;D�WZ�W�a���\0ފ�CG8�R �	�\n������P�A��&������,�pfV|@N�b�\$�[�I����������Z�@Zd\\\"�|��+�ۮ��tz�o\$�\0[����y�E���ə�bhU1��,�r\$�o8D���F��V&ځ5�h}��N�ͳ&�絕ef�ǙY��:�^z�VPu	W�Z\"r�:�h�w��h#1��O���K�hq`妄����v|�˧:wD�j�(W�������碌�?�;|Z��%�%ڡ�r@[����B�&������#���ُ��:)��Y6����&��	@�	���I��!����� ���2M���O;���W��)��C��FZ�p!��a��*F�b�I��;���#Ĥ9����S�/S�A�`z�L*�8�+��N���-�M���-kd���Li�J�·�Jn��b��>,�V�SP�8��>�w��\"E.��Rz`��u_����E\\��ɫ�3P��ӥs]���goVS���\n��	*�\r��7)�ʄ�m�PW�UՀ��ǰ���f��ܓi�ƅkЌ\r�('W`�Bd�/h*�A�l�M��_\n�������O��T�5�&A�2é`��\\R�E\"_�_��.7�M�6d;�<?��)(;���}K�[�����Z?��yI ��1p�bu\0�������{��\ri���E�`�~\n��=��o���'�����v�P�yC\0��\$�8�T�/m1GT��l��}o�e�=Gtb�I/[0�%�o|�Sy����^o��;�����@T�b�*��i���PZT	��Ӄ\0\$��>��e�L�J_�7�-Rf�0\"���6g��z\r�a3�a��6+3�DBg�3ՁY�'Y�d��x6I3_2}���;����`�@�b������ {�C:SuM\nļ��SK\0�B;T�`�8�G��x�I�`5��#\"N�����ҭ���v&�e�kD�sq���.F���<��s� h�e6������*�b�iS܊��̮�`��ق���+\0���5�LLBT�Ă�wdXc��F��1�&�0�^�P)\$\\8i����(L��x)�n@�C��?�\$�Yvy.\$(\0@p�u\rxo�����tb�{�7B�k\r�`Mt(� BTP��<�G�\$�>�^pC*���!.&�Lx�����\0]�4��\0���P��a�����\n�N�K�Ѧ�p�\$B˭@��wV����f#��r��|�Y�/q��YZ�VXB,w�܄hF�G���Sg_?3,�	sЊ�E�#�^�,�����4�R;���J��,<�e(V�#C���`�/8\rv�qmpjBA������R@\nЭ�v�w��^�����7��:�=J�P��i����yɥ���\0007E�(IU2!�҈f�C��I�8G�¢?e^`����A]�ę�(p�}�އ{�!�Fm(�R���B��eۂ@�)��,JO�����\n��\0�%]��w�LA�x��9�ڔ���Ɍ,�b�:��/�Q�HE|;��D���	�\$������o�b�+�[@��!Лd�Q�B�7ŞER\n�yW�|�EPJ+��:�X\0�Է A\rnˆi�� ����])q�<<jH�yܒ�\"!N�t�D0F��[ a�)'�V}Q9\$�n@O9x�X洔;�#�u�4 G�A��f.\\5�7b���\rz�h�~�y\0��-�G\0/7K����`+��,Q�:��⇃s�^�54��8ݍ�y\0����r��u���;�,8��\0��4t�5\n�_�ach� ���bZZ�����dQq����Z(�%@���!��@��%����Q�?�����C�k�#�	8 ���|dXc�����2U�I�!�^�-W�T9���@�����=�\0Ҙ\$��2��J�eϤ�t�2����K�T+��\0��� �\0��@<H��G�Bd}����ځzb������	^I�����hSI-��K��J���\r%R��\\&�hlNЊI1\n�J�9n#�4�KU1�vK�����ha�m�M#�����;L�*?�Le�\n���(q��LGp���8` H꩘4T�J`\"FM�%@�)QH�gVO4��)�\$��3\" ����*�2�0!��o\0��WM�P�rb�L�1qJ�Rŕ����,R\$��3�w��^�*�{��AAfh�U��YW\n�4���.�2,���a�Z���&=B?�b�@O/��`I���np��	WP�a0���`�x��YgH&q�����xC��4�ȉ��bXÌ.��,�H/�8N�E-�	�\"��2 l��R���ev\0���[��g�fr���\0P��,S�'�ׁp�р������.�08��\"����5үg����4���@\rFpK�Q��\0���e@�l�p?\0001���_�X>#�����<�H&(����ܠ/kD�t��`\$\"Zȓ6	�M�X�@jX������� l��E\0�Ɲ��t\0m���\0^��\$��S���f�2�1\0\r�/X�N6b���*`y�P�PW���t c��f� ��pf��'�:\0��ti\r���T9�\0�f�� ��O&<��rc\n�	�f6!9��������P^+ȼ���D�O�n��q��E�Y�\" n��%L��#��z\\�'1:��� -���|�V��X���c���&f�&�^a�1��꒘�H5Q��q5(>D�K�إIG��J:����J���{�Sd�&��)	��Ke��@L w�-�\\3��M�*Q�t�*�𧵤3BC� �\$qs�J�<F��x�8!QpFI��4��)��D,3�-�������::�!R��\$S ,�(!.�Q1\$N(�*��F�\0�G���8���?N��_X�̨�6��L��#	�\0T3D��6s�B�hFu���J'��\ne� ����'�V��Dp��D&)ѣ�z@9�\$��(� ����ĦY͞��@1,zEJ���W0��\0du�dP\"-MQ4ܦ�k���G�	N���B򬀶��2��Dr_��\0�N���Ν��/�ӈI�>SA{�e9�L�!W9Y�ᐄ������Y�B�b��h��x���;��ӄ/�c^3�?�Q�v������>j=O�1���T��t\\�B�-�^Ň)S��)���F\r����HR�����6I3+y6W�lxyA�`)N�(P��Y/`�x�]��Ș�II�UH�c�����T�g\\\r8��R��:�W��He\\|�MyD�T#�Cj�}����F��#�HH@R`��&�d Zt��@�����Y,)		n�����E6!%/��󦮱������:Q?)��П �t܆�\0��u=�s<����%��R+Z��\nS�}r |Efu[z���z?8T�B��M\$�Ū�\\bv���\$�z�W��IՐ���Ou\\����<��x � ��+�����|.0I+�U0 �D�[4\0/��\0|`��&� ����Y�����!�M�EZp.��:u�h�wMW%�m:c�N��k�[�`��LS[R!��U9_�X&���qt��]�ג2�UsY�w�|�0�\r���k�:�2���5l\rb\0��t�u�w�}E��A��n8�l�3z⁮� '�Z&�R@���M�Pʒ]��\n��\">(L��G��*vD�QA�D��<���u�\0\n��	Zv0Ůzl(�V\\��HB��2�\0�Hp�9B�0Hb��pY\$��cR����0��@Z}~�αE�����H\n\rڹ���w\$�e��\r(q ��!�\\�3�LX����b\n��\\���{gi�9�Z8�w��#�n9ń�3�!���a���5ma�ڹ:����T�2p&�`^L幋�n����p6����]s�[�,�e���o�W	�����\r�p.N4j:ks�^�\\#�@_��C�pB��� �)�	��R0��b~���Z�(:�bLC,	�QB�&\"[��.Q��U�#j��p�*�md�DZԬ@[�����v�M[>�g!�s,ǣ�UrՖ�p�jt�PBf��-ݦ\0`�3ҍ���-�XUB���A�*���@ON��\\VS�4.��	r�ʖM\"�h�ʌ�O�i�壌�@Z�[��(�8/(@��W��W����IYeyE�zb�V��o8����x��刯.���nx\$\n�8��\n<�ܙ ����R�d!ˊ-�U5�@{Y���A\n�;�*KR��qCP-�3jY��M�k��`�&�^5\0��l�RV\r���\rK�M�m�#@��!�޳����g�@�5:������igP:��3��O}��'Z�׍D�X�����\0�8�h��Ww���8	����h_T�`g�Ͷܫ���H	9���0X�̐B�-J-gC�(��t��\$�!�^�n�*HD�!\n:�Ɉ�t�&�'j�����G�e'�Vr7Y�����cjх*��~�uzU�i@d��\\P\r\rIp\0`����);�I�g�]W4/�@����)��L ��a����-2o��\"�a�����/r���\\p L\r����ڨ�'��/���HP���~��\n��9^`�u;�k���ܣھ�m�J\$ �r:k\rT9Y�*.l�,Z�m\r�B��q�J|a���بK��V@�d���#��\$Ϊ��� �Bk����aI���[�������G�����u\n	C�W�+�͂�m7��k�8����{�0ǍG����Bڊ������v��^ogq��=��a���j��f}���#8s%�����9<�  ���R<�h��s�����9 �q�K�w]\\�ĸJt���� ���(�:M�1�87b߬u�NP�8�c�'��\n��;l��S+��/�S�ǜ��Mu)�~!���e��HF�_�a�f8�]|�u@`�!3w�5��*gYNE.�k�r�j8t#�*|�«�̴��!a�w��eq>�p:E�K�'�-����i\$����11�&�\nt��t��\$(Y��PsX^�V�\r�b�	c��;�v�f��uS��s|��f����)�1�[�666'} �p��Py@�q��ʺ�����������j�A�U���t�C�%�+f~����)W]�&�j�zC'�ϕ��\$����R�(i��+�F��aSzS�Q��av�>Y�|�gOE�Ѐ�hZ��+�٠j[�ABtȰ�YF��'�|\r�Kf�����iP�t�JKv�_�p�`�y�,��W\"���A��K��@t�(6���,��I@p	@����\r �S;�D	nh4�g ���IZ�)�4�� ڟH��X��i��QB��2���K�	�a 4ER`��\"�a��^l�R(���%��_�r2ָk��\\.�Nns٧Y�\"\r�G�l�m��BD��`p\r=�F;�I�OF9�s3-n����A�-�j�-�V1/h��4��A&�\\�y�v���3_�D�	z�]���[���k\$5R�s{u��||��WD�T���K�ɉ��)�\n��싁p��u��\n�Bk���l���|��]�{	\r�NL �5�Z������� ��:J!�����?ԧ���u}��DNה a\\�4;a��=HJ�+?db��#vy 'e;0��hX��u��K�?7`PuO���Ø^�׉��&�fv��]]�~�R|���=X�3��@��������jTڐJ���vpO��Ǆ�'m�a���_&H��T�d{X�,�V�5����m�ZZ��K��d�)���<�P\nK4�N���ػݟ\n����et���R>i��E�@�(}3�\$�Ԋ�w\"��ai�\n�x9,���)	�k�m��cG`�2@Ԯ��Cr\"R=�.�y�&��P���n�G\0|<^��H����L�2�C�j������~���aP�i��ZrGf��\r��\rBՒ�Ȕl�2u��y4;@H�\rq����I��ie�X\"Ls^�X����e\n�=��ա�V�FC�Ӻ�>7\$��^�\$��\0�p�&���Y���n�A����w��#\"���:��?���kf�.P����`bl�Z�\$ĳ���3l'[\r��&,\0�m�*@�઼�8;fk%u&�ހ���UJc��@��i�MK�P��*<�p~��2k�l���8I�\$1�I��(Np��-f:��<��\$�AW��L�9�z	�g\0�(�>�0�^Yq��\\m��z�u��Zl����.��υM�rK'{��z��_�9K�jx_e����G��-%��X�/�d��\$�,�<KRO�i����!(�:�n!��ڻU)*��̭V���Vm�����Y�u���Uc9ܑ���)��Kk����QyS������Ia��|л2�����x0��D1��\rl�DLM��w���(;d�;\$���x;OBk�^ބ��f��6�k�[:�MЀ{�~�Ӄy������H��[O����b_G@�찐���q�楶y҇�8�F�H��(yj�s\"�\\�%��w1N���k�Z��#S 0�N-�tZ��@�@\"�`J5�w0I��@^,��Dw�c9F�\$!��|qc'?S�ƉI�� \\,RIʼ,���H�ej��E2�}f��N�ˮk��qĉ���!s.�R\\;���2���Δ9����N��l��x�2��я������vmIeI�Zz�����ΌK�`�p�.na4<*4�d�ÿ��v#�1��+9���q`oL��\0�A-���	�j�}��q~W䙀�\0���H@s�ۭ�U��}�WP	�����^�)���4FY�m�\n]�{�{T�W�w%o8D@�:6�������F_������\"�欄u��/�;�G��`	���\"/���	�-ay��\"�^��Q�7��N|����d�Rh8TMy�����N�>�D2<\0�S@��@=�7�����8���)T���h�nњ\0w(]���r�ܕ�;��ر���!��\$\n�P @�P<Ȗ4�B�����P!��M�pS�b�M�����\r4!���J��s��(�i�(I8]}����;����T2��F�j�@|�:�0�\$#��܅�p�y�򤄷q�Eg��\0�I3�kΫ0�oJ�� -ͷ�L�)��t�\"�k{BnCZ����R?h��z7�eS+Po�<Xu\rb�R��N�:iEE�h�P�QAL{�Q~�)Ш�-i�݉��QD���[�{`�Qv��;���o�p秈�<���p����Dw�Z���t{� }�m�\\_`�qvF�Z@��sf�_/��N����#^��Ɵ����2��q��~h��|X�4���D�:�����	٩�OHY\"�i���~�;S|�7��Hn��@��.�@��>��_��T_W�J�����7}5C�����;��Z���HV憽�����^h�}�/��{��=D;��Z�����hC�'��}��U�>_�5�Ɩj�?;���Pu��O�@����~�E�����j�R��14	��R���;}A㦃H~�\$�X�~o������������(�_�E����C�*��0��p2��(d_����=�\"�Q���������ZO[|����b7��1���#e@���v hT⛁�?����oY�j.��5(Rk~��� �?�e=p�ء׊�\0���=vx.��=b��K�쫲f��WK@\0V�O�L\0V�⭨a�L�*� ^�@o�OÆ�^��L����jJ^@m� �v��n�l�K������D���CV/S�\0��}M&8��߰/�~��\"��2��^��6H���<��&���Ą0`�fx��7��kJ����HZ�t<��M�U&�T��a�>dX�m��X�V���N&2-���~�<�M�@&T\$����]�#P=���D\r7�b�xSM7�6��\$	���x��l�@���	4��3��~�P#����p�`܄�g��� \$�c(\0�\\�;��\"V!�q`�>&X#�6Fh!aQ���\r�?`F(������[���x���\0utAӆ�V��'������i\"5�?�Mmȏ�A���x=F�pp��d^��WCdp�A����?���%X��\"���\"\\�&���F�щ@�f�0��K��e�z9� B`ӓO��2��7B@2�");
    } elseif ($_GET["file"] == "jush.js") {
        header("Content-Type: text/javascript; charset=utf-8");
        echo
        lzw_decompress("v0��F����==��FS	��_6MƳ���r:�E�CI��o:�C��Xc��\r�؄J(:=�E���a28�x�?�'�i�SANN���xs�NB��Vl0���S	��Ul�(D|҄��P��>�E�㩶yHch��-3Eb�� �b��pE�p�9.����~\n�?Kb�iw|�`��d.�x8EN��!��2��3���\r���Y���y6GFmY�8o7\n\r�0��\0�Dbc�!�Q7Шd8���~��N)�Eг`�Ns��`�S)�O���/�<�x�9�o�����3n��2�!r�:;�+�9�CȨ���\n<�`��b�\\�?�`�4\r#`�<�Be�B#�N ��\r.D`��j�4���p�ar��㢺�>�8�\$�c��1�c���c����{n7����A�N�RLi\r1���!�(�j´�+��62�X�8+����.\r����!x���h�'��6S�\0R����O�\n��1(W0���7q��:N�E:68n+��մ5_(�s�\r��/m�6P�@�EQ���9\n�V-���\"�.:�J��8we�q�|؇�X�]��Y X�e�zW�� �7��Z1��hQf��u�j�4Z{p\\AU�J<��k��@�ɍ��@�}&���L7U�wuYh��2��@�u� P�7�A�h����3Û��XEͅZ�]�l�@Mplv�)� ��HW���y>�Y�-�Y��/�������hC�[*��F�#~�!�`�\r#0P�C˝�f������\\���^�%B<�\\�f�ޱ�����&/�O��L\\jF��jZ�1�\\:ƴ>�N��XaF�A�������f�h{\"s\n�64������?�8�^p�\"띰�ȸ\\�e(�P�N��q[g��r�&�}Ph���W��*��r_s�P�h���\n���om������#���.�\0@�pdW �\$Һ�Q۽Tl0� ��HdH�)��ۏ��)P���H�g��U����B�e\r�t:��\0)\"�t�,�����[�(D�O\nR8!�Ƭ֚��lA�V��4�h��Sq<��@}���gK�]���]�=90��'����wA<����a�~��W��D|A���2�X�U2��yŊ��=�p)�\0P	�s��n�3�r�f\0�F���v��G��I@�%���+��_I`����\r.��N���KI�[�ʖSJ���aUf�Sz���M��%��\"Q|9��Bc�a�q\0�8�#�<a��:z1Uf��>�Z�l������e5#U@iUG��n�%Ұs���;gxL�pP�?B��Q�\\�b��龒Q�=7�:��ݡQ�\r:�t�:y(� �\n�d)���\n�X;����CaA�\r���P�GH�!���@�9\n\nAl~H���V\ns��ի�Ư�bBr���������3�\r�P�%�ф\r}b/�Α\$�5�P�C�\"w�B_��U�gAt��夅�^Q��U���j���Bvh졄4�)��+�)<�j^�<L��4U*���Bg�����*n�ʖ�-����	9O\$��طzyM�3�\\9���.o�����E(i������7	tߚ�-&�\nj!\r��y�y�D1g���]��yR�7\"������~����)TZ0E9M�YZtXe!�f�@�{Ȭyl	8�;���R{��8�Į�e�+UL�'�F�1���8PE5-	�_!�7��[2�J��;�HR��ǹ�8p痲݇@��0,ծpsK0\r�4��\$sJ���4�DZ��I��'\$cL�R��MpY&����i�z3G�zҚJ%��P�-��[�/x�T�{p��z�C�v���:�V'�\\��KJa��M�&���Ӿ\"�e�o^Q+h^��iT��1�OR�l�,5[ݘ\$��)��jLƁU`�S�`Z^�|��r�=��n登��TU	1Hyk��t+\0v�D�\r	<��ƙ��jG���t�*3%k�YܲT*�|\"C��lhE�(�\r�8r��{��0����D�_��.6и�;����rBj�O'ۜ���>\$��`^6��9�#����4X��mh8:��c��0��;�/ԉ����;�\\'(��t�'+�����̷�^�]��N�v��#�,�v���O�i�ϖ�>��<S�A\\�\\��!�3*tl`�u�\0p'�7�P�9�bs�{�v�{��7�\"{��r�a�(�^��E����g��/���U�9g���/��`�\nL\n�)���(A�a�\" ���	�&�P��@O\n師0�(M&�FJ'�! �0�<�H�������*�|��*�OZ�m*n/b�/�������.��o\0��dn�)����i�:R���P2�m�\0/v�OX���Fʳψ���\"�����0�0�����0b��gj��\$�n�0}�	�@�=MƂ0n�P�/p�ot������.�̽�g\0�)o�\n0���\rF����b�i��o}\n�̯�	NQ�'�x�Fa�J���L������\r��\r����0��'��d	oep��4D��ʐ�q(~�� �\r�E��pr�QVFH�l��Kj���N&�j!�H`�_bh\r1���n!�Ɏ�z�����\\��\r���`V_k��\"\\ׂ'V��\0ʾ`AC������V�`\r%�����\r����k@N����B�횙� �!�\n�\0Z�6�\$d��,%�%la�H�\n�#�S\$!\$@��2���I\$r�{!��J�2H�ZM\\��hb,�'||cj~g�r�`�ļ�\$���+�A1�E���� <�L��\$�Y%-FD��d�L焳��\n@�bVf�;2_(��L�п��<%@ڜ,\"�d��N�er�\0�`��Z��4�'ld9-�#`��Ŗ����j6�ƣ�v���N�͐f��@܆�&�B\$�(�Z&���278I ��P\rk\\���2`�\rdLb@E��2`P( B'�����0�&��{���:��dB�1�^؉*\r\0c<K�|�5sZ�`���O3�5=@�5�C>@�W*	=\0N<g�6s67Sm7u?	{<&L�.3~D��\rŚ�x��),r�in�/��O\0o{0k�]3>m��1\0�I@�9T34+ԙ@e�GFMC�\rE3�Etm!�#1�D @�H(��n ��<g,V`R]@����3Cr7s~�GI�i@\0v��5\rV�'������P��\r�\$<b�%(�Dd��PW����b�fO �x\0�} ��lb�&�vj4�LS��ִԶ5&dsF M�4��\".H�M0�1uL�\"��/J`�{�����xǐYu*\"U.I53Q�3Q��J��g��5�s���&jь��u�٭ЪGQMTmGB�tl-c�*��\r��Z7���*hs/RUV����B�Nˈ�����Ԋ�i�Lk�.���t�龩�rYi���-S��3�\\�T�OM^�G>�ZQj���\"���i��MsS�S\$Ib	f���u����:�SB|i��Y¦��8	v�#�D�4`��.��^�H�M�_ռ�u��U�z`Z�J	e��@Ce��a�\"m�b�6ԯJR���T�?ԣXMZ��І��p����Qv�j�jV�{���C�\r��7�Tʞ� ��5{P��]�\r�?Q�AA������2񾠓V)Ji��-N99f�l Jm��;u�@�<F�Ѡ�e�j��Ħ�I�<+CW@�����Z�l�1�<2�iF�7`KG�~L&+N��YtWH飑w	����l��s'g��q+L�zbiz���Ţ�.Њ�zW�� �zd�W����(�y)v�E4,\0�\"d��\$B�{��!)1U�5bp#�}m=��@�w�	P\0�\r�����`O|���	�ɍ����Y��JՂ�E��Ou�_�\n`F`�}M�.#1��f�*�ա��  �z�uc���� xf�8kZR�s2ʂ-���Z2�+�ʷ�(�sU�cD�ѷ���X!��u�&-vP�ر\0'L�X �L����o	��>�Վ�\r@�P�\rxF��E��ȭ�%����=5N֜��?�7�N�Å�w�`�hX�98 �����q��z��d%6̂t�/������L��l��,�Ka�N~�����,�'�ǀM\rf9�w��!x��x[�ϑ�G�8;�xA��-I�&5\$�D\$���%��xѬ���´���]����&o�-3�9�L��z���y6�;u�zZ ��8�_�ɐx\0D?�X7����y�OY.#3�8��ǀ�e�Q�=؀*��G�wm ���Y�����]YOY�F���)�z#\$e��)�/�z?�z;����^��F�Zg�����������`^�e����#�������?��e��M��3u�偃0�>�\"?��@חXv�\"������*Ԣ\r6v~��OV~�&ר�^g���đٞ�'��f6:-Z~��O6;zx��;&!�+{9M�ٳd� \r,9���W��ݭ:�\r�ٜ��@睂+��]��-�[g��ۇ[s�[i��i�q��y��x�+�|7�{7�|w�}����E��W��Wk�|J؁��xm��q xwyj���#��e��(�������ߞþ��� {��ڏ�y���M���@��ɂ��Y�(g͚-��������J(���@�;�y�#S���Y��p@�%�s��o�9;�������+��	�;����ZNٯº��� k�V��u�[�x��|q��ON?���	�`u��6�|�|X����س|O�x!�:���ϗY]�����c���\r�h�9n�������8'������\rS.1��USȸ��X��+��z]ɵ��?����C�\r��\\����\$�`��)U�|ˤ|Ѩx'՜����<�̙e�|�ͳ����L���M�y�(ۧ�l�к�O]{Ѿ�FD���}�yu��Ē�,XL\\�x��;U��Wt�v��\\OxWJ9Ȓ�R5�WiMi[�K��f(\0�dĚ�迩�\r�M����7�;��������6�KʦI�\r���xv\r�V3���ɱ.��R������|��^2�^0߾\$�Q��[�D��ܣ�>1'^X~t�1\"6L���+��A��e�����I��~����@����pM>�m<��SK��-H���T76�SMfg�=��GPʰ�P�\r��>�����2Sb\$�C[���(�)��%Q#G`u��Gwp\rk�Ke�zhj��zi(��rO�������T=�7���~�4\"ef�~�d���V�Z���U�-�b'V�J�Z7���)T��8.<�RM�\$�����'�by�\n5����_��w����U�`ei޿J�b�g�u�S��?��`���+��� M�g�7`���\0�_�-���_��?�F�\0����X���[��J�8&~D#��{P���4ܗ��\"�\0��������@ғ��\0F ?*��^��w�О:���u��3xK�^�w���߯�y[Ԟ(���#�/zr_�g��?�\0?�1wMR&M���?�St�T]ݴG�:I����)��B�� v����1�<�t��6�:�W{���x:=��ޚ��:�!!\0x�����q&��0}z\"]��o�z���j�w�����6��J�P۞[\\ }��`S�\0�qHM�/7B��P���]FT��8S5�/I�\r�\n ��O�0aQ\n�>�2�j�;=ڬ�dA=�p�VL)X�\n¦`e\$�TƦQJ����lJ����y�I�	�:����B�bP���Z��n����U;>_�\n	�����`��uM򌂂�֍m����Lw�B\0\\b8�M��[z��&�1�\0�	�\r�T������+\\�3�Plb4-)%Wd#\n��r��MX\"ϡ�(Ei11(b`@f����S���j�D��bf�}�r����D�R1���b��A��Iy\"�Wv��gC�I�J8z\"P\\i�\\m~ZR��v�1ZB5I��i@x����-�uM\njK�U�h\$o��JϤ!�L\"#p7\0� P�\0�D�\$	�GK4e��\$�\nG�?�3�EAJF4�Ip\0��F�4��<f@� %q�<k�w��	�LOp\0�x��(	�G>�@�����9\0T����GB7�-�����G:<Q��#���Ǵ�1�&tz��0*J=�'�J>���8q��Х���	�O��X�F��Q�,����\"9��p�*�66A'�,y��IF�R��T���\"��H�R�!�j#kyF���e��z�����G\0�p��aJ`C�i�@�T�|\n�Ix�K\"��*��Tk\$c��ƔaAh��!�\"�E\0O�d�Sx�\0T	�\0���!F�\n�U�|�#S&		IvL\"����\$h���EA�N\$�%%�/\nP�1���{��) <���L���-R1��6���<�@O*\0J@q��Ԫ#�@ǵ0\$t�|�]�`��ĊA]���Pᑀ�C�p\\pҤ\0���7���@9�b�m�r�o�C+�]�Jr�f��\r�)d�����^h�I\\�. g��>���8���'�H�f�rJ�[r�o���.�v���#�#yR�+�y��^����F\0᱁�]!ɕ�ޔ++�_�,�\0<@�M-�2W���R,c���e2�*@\0�P ��c�a0�\\P���O���`I_2Qs\$�w��=:�z\0)�`�h�������\nJ@@ʫ�\0�� 6qT��4J%�N-�m����.ɋ%*cn��N�6\"\r͑�����f�A���p�MۀI7\0�M�>lO�4�S	7�c���\"�ߧ\0�6�ps�����y.��	���RK��PAo1F�tI�b*��<���@�7�˂p,�0N��:��N�m�,�xO%�!��v����gz(�M���I��	��~y���h\0U:��OZyA8�<2����us�~l���E�O�0��0]'�>��ɍ�:���;�/��w�����'~3GΖ~ӭ����c.	���vT\0c�t'�;P�\$�\$����-�s��e|�!�@d�Obw��c��'�@`P\"x����0O�5�/|�U{:b�R\"�0�шk���`BD�\nk�P��c��4�^ p6S`��\$�f;�7�?ls��߆gD�'4Xja	A��E%�	86b�:qr\r�]C8�c�F\n'ьf_9�%(��*�~��iS����@(85�T��[��Jڍ4�I�l=��Q�\$d��h�@D	-��!�_]��H�Ɗ�k6:���\\M-����\r�FJ>\n.��q�eG�5QZ����' ɢ���ہ0��zP��#������r���t����ˎ��<Q��T��3�D\\����pOE�%)77�Wt�[��@����\$F)�5qG0�-�W�v�`�*)Rr��=9qE*K\$g	��A!�PjBT:�K���!��H� R0?�6�yA)B@:Q�8B+J�5U]`�Ҭ��:���*%Ip9�̀�`KcQ�Q.B��Ltb��yJ�E�T��7���Am�䢕Ku:��Sji� 5.q%LiF��Tr��i��K�Ҩz�55T%U��U�IՂ���Y\"\nS�m���x��Ch�NZ�UZ���( B��\$Y�V��u@蔻����|	�\$\0�\0�oZw2Ҁx2���k\$�*I6I�n�����I,��QU4�\n��).�Q���aI�]����L�h\"�f���>�:Z�>L�`n�ض��7�VLZu��e��X����B���B�����Z`;���J�]�����S8��f \nڶ�#\$�jM(��ޡ����a�G��+A�!�xL/\0)	C�\n�W@�4�����۩� ��RZ����=���8�`�8~�h��P ��\r�	���D-FyX�+�f�QSj+X�|��9-��s�x�����+�V�cbp쿔o6H�q�����@.��l�8g�YM��WMP��U��YL�3Pa�H2�9��:�a�`��d\0�&�Y��Y0٘��S�-��%;/�T�BS�P�%f������@�F�(�֍*�q +[�Z:�QY\0޴�JUY֓/���pkzȈ�,�𪇃j�ꀥW�״e�J�F��VBI�\r��pF�Nقֶ�*ը�3k�0�D�{����`q��ҲBq�e�D�c���V�E���n����FG�E�>j�����0g�a|�Sh�7u�݄�\$���;a��7&��R[WX���(q�#���P���ז�c8!�H���VX�Ď�j��Z������Q,DUaQ�X0��ը���Gb��l�B�t9-oZ���L���­�pˇ�x6&��My��sҐ����\"�̀�R�IWU`c���}l<|�~�w\"��vI%r+��R�\n\\����][��6�&���ȭ�a�Ӻ��j�(ړ�Tѓ��C'��� '%de,�\n�FC�эe9C�N�Ѝ�-6�Ueȵ��CX��V������+�R+�����3B��ڌJ�虜��T2�]�\0P�a�t29��(i�#�aƮ1\"S�:�����oF)k�f���Ъ\0�ӿ��,��w�J@��V򄎵�q.e}KmZ����XnZ{G-���ZQ���}��׶�6ɸ���_�؁Չ�\n�@7�` �C\0]_ ��ʵ����}�G�WW: fCYk+��b۶���2S,	ڋ�9�\0﯁+�W�Z!�e��2������k.Oc��(v̮8�DeG`ۇ�L���,�d�\"C���B-�İ(����p���p�=����!�k������}(���B�kr�_R�ܼ0�8a%ۘL	\0���b������@�\"��r,�0T�rV>����Q��\"�r��P�&3b�P��-�x���uW~�\"�*舞�N�h�%7���K�Y��^A����C����p����\0�..`c��+ϊ�GJ���H���E����l@|I#Ac��D��|+<[c2�+*WS<�r��g���}��>i�݀�!`f8�(c����Q�=f�\n�2�c�h4�+q���8\na�R�B�|�R����m��\\q��gX����ώ0�X�`n�F���O p��H�C��jd�f��EuDV��bJɦ��:��\\�!mɱ?,TIa���aT.L�]�,J��?�?��FMct!a٧R�F�G�!�A���rr�-p�X��\r��C^�7���&�R�\0��f�*�A\n�՛H��y�Y=���l�<��A�_��	+��tA�\0B�<Ay�(fy�1�c�O;p���ᦝ`�4СM��*��f�� 5fvy {?���:y��^c��u�'���8\0��ӱ?��g��� 8B��&p9�O\"z���rs�0��B�!u�3�f{�\0�:�\n@\0����p���6�v.;�����b�ƫ:J>˂��-�B�hkR`-����aw�xEj����r�8�\0\\����\\�Uhm� �(m�H3̴�S����q\0��NVh�Hy�	��5�M͎e\\g�\n�IP:Sj�ۡٶ�<���x�&�L��;nfͶc�q��\$f�&l���i�����0%yΞ�t�/��gU̳�d�\0e:��h�Z	�^�@��1��m#�N��w@��O��zG�\$�m6�6}��ҋ�X'�I�i\\Q�Y���4k-.�:yz���H��]��x�G��3��M\0��@z7���6�-DO34�ދ\0Κ��ΰt\"�\"vC\"Jf�Rʞ��ku3�M��~����5V ��j/3���@gG�}D���B�Nq��=]\$�I��Ӟ�3�x=_j�X٨�fk(C]^j�M��F��ա��ϣCz��V��=]&�\r�A<	������6�Ԯ�״�`jk7:g��4ծ��YZq�ftu�|�h�Z��6��i〰0�?��骭{-7_:��ސtѯ�ck�`Y��&���I�lP`:�� j�{h�=�f	��[by��ʀoЋB�RS���B6��^@'�4��1U�Dq}��N�(X�6j}�c�{@8���,�	�PFC���B�\$mv���P�\"��L��CS�]����E���lU��f�wh{o�(��)�\0@*a1G� (��D4-c��P8��N|R���VM���n8G`e}�!}���p�����@_���nCt�9��\0]�u��s���~�r��#Cn�p;�%�>wu���n�w��ݞ�.���[��hT�{��值	�ˁ��J���ƗiJ�6�O�=������E��ٴ��Im���V'��@�&�{��������;�op;^��6Ŷ@2�l���N��M��r�_ܰ�Í�` �( y�6�7�����ǂ��7/�p�e>|��	�=�]�oc����&�xNm���烻��o�G�N	p����x��ý���y\\3����'�I`r�G�]ľ�7�\\7�49�]�^p�{<Z��q4�u�|��Qۙ��p���i\$�@ox�_<���9pBU\"\0005�� i�ׂ��C�p�\n�i@�[��4�jЁ�6b�P�\0�&F2~������U&�}����ɘ	��Da<��zx�k���=���r3��(l_���FeF���4�1�K	\\ӎld�	�1�H\r���p!�%bG�Xf��'\0���	'6��ps_��\$?0\0�~p(�H\n�1�W:9�͢��`��:h�B��g�B�k��p�Ɓ�t��EBI@<�%����` �y�d\\Y@D�P?�|+!��W��.:�Le�v,�>q�A���:���bY�@8�d>r/)�B�4���(���`|�:t�!����?<�@���/��S��P\0��>\\�� |�3�:V�uw���x�(����4��ZjD^���L�'���C[�'�����jº[�E�� u�{KZ[s���6��S1��z%1�c��B4�B\n3M`0�;����3�.�&?��!YA�I,)��l�W['��ITj���>F���S���BбP�ca�ǌu�N����H�	LS��0��Y`���\"il�\r�B���/����%P���N�G��0J�X\n?a�!�3@M�F&ó����,�\"���lb�:KJ\r�`k_�b��A��į��1�I,�����;B,�:���Y%�J���#v��'�{������	wx:\ni����}c��eN���`!w��\0�BRU#�S�!�<`��&v�<�&�qO�+Σ�sfL9�Q�Bʇ����b��_+�*�Su>%0�����8@l�?�L1po.�C&��ɠB��qh�����z\0�`1�_9�\"���!�\$���~~-�.�*3r?�ò�d�s\0����>z\n�\0�0�1�~���J����|Sޜ��k7g�\0��KԠd��a��Pg�%�w�D��zm�����)����j�����`k���Q�^��1���+��>/wb�GwOk���_�'��-CJ��7&����E�\0L\r>�!�q́���7����o��`9O`�����+!}�P~E�N�c��Q�)��#��#�����������J��z_u{��K%�\0=��O�X�߶C�>\n���|w�?�F�����a�ϩU����b	N�Y��h����/��)�G��2���K|�y/�\0��Z�{��P�YG�;�?Z}T!�0��=mN����f�\"%4�a�\"!�ޟ����\0���}��[��ܾ��bU}�ڕm��2�����/t���%#�.�ؖ��se�B�p&}[˟��7�<a�K���8��P\0��g��?��,�\0�߈r,�>���W����/��[�q��k~�CӋ4��G��:��X��G�r\0������L%VFLUc��䑢��H�ybP��'#��	\0п���`9�9�~���_��0q�5K-�E0�b�ϭ�����t`lm����b��Ƙ; ,=��'S�.b��S���Cc����ʍAR,����X�@�'��8Z0�&�Xnc<<ȣ�3\0(�+*�3��@&\r�+�@h, ��\$O���\0Œ��t+>����b��ʰ�\r�><]#�%�;N�s�Ŏ����*��c�0-@��L� >�Y�p#�-�f0��ʱa�,>��`����P�:9��o���ov�R)e\0ڢ\\����\nr{îX����:A*��.�D��7�����#,�N�\r�E���hQK2�ݩ��z�>P@���	T<��=�:���X�GJ<�GAf�&�A^p�`���{��0`�:���);U !�e\0����c�p\r�����:(��@�%2	S�\$Y��3�hC��:O�#��L��/����k,��K�oo7�BD0{���j��j&X2��{�}�R�x��v���أ�9A����0�;0�����-�5��/�<�� �N�8E����	+�Ѕ�Pd��;���*n��&�8/jX�\r��>	PϐW>K��O��V�/��U\n<��\0�\nI�k@��㦃[��Ϧ²�#�?���%���.\0001\0��k�`1T� ����ɐl�������p���������< .�>��5��\0��	O�>k@Bn��<\"i%�>��z��������3�P�!�\r�\"��\r �>�ad���U?�ǔ3P��j3�䰑>;���>�t6�2�[��޾M\r�>��\0��P���B�Oe*R�n���y;� 8\0���o�0���i���3ʀ2@����?x�[����L�a����w\ns����A��x\r[�a�6�clc=�ʼX0�z/>+����W[�o2���)e�2�HQP�DY�zG4#YD����p)	�H�p���&�4*@�/:�	�T�	���aH5���h.�A>��`;.���Y��a	���t/ =3��BnhD?(\n�!�B�s�\0��D�&D�J��)\0�j�Q�y��hDh(�K�/!�>�h,=�����tJ�+�S��,\"M�Ŀ�N�1�[;�Т��+��#<��I�Zğ�P�)��LJ�D��P1\$����Q�>dO��v�#�/mh8881N:��Z0Z���T �B�C�q3%��@�\0��\"�XD	�3\0�!\\�8#�h�v�ib��T�!d�����V\\2��S��Œ\nA+ͽp�x�iD(�(�<*��+��E��T���B�S�CȿT���� e�A�\"�|�u�v8�T\0002�@8D^oo�����|�N������J8[��3����J�z׳WL\0�\0��Ȇ8�:y,�6&@�� �E�ʯݑh;�!f��.B�;:���[Z3������n���ȑ��A���qP4,��Xc8^��`׃��l.����S�hޔ���O+�%P#Ρ\n?��IB��eˑ�O\\]��6�#��۽؁(!c)�N����?E��B##D �Ddo��P�A�\0�:�n�Ɵ�`  ��Q��>!\r6�\0��V%cb�HF�)�m&\0B�2I�5��#]���D>��3<\n:ML��9C���0��\0���(ᏩH\n����M�\"GR\n@���`[���\ni*\0��)������u�)��Hp\0�N�	�\"��N:9q�.\r!���J��{,�'����4�B���lq���Xc��4��N1ɨ5�Wm��3\n��F��`�'��Ҋx��&>z>N�\$4?����(\n쀨>�	�ϵP�!Cq͌��p�qGLqq�G�y�H.�^��\0z�\$�AT9Fs�Ѕ�D{�a��cc_�G�z�)� �}Q��h��HBָ�<�y!L����!\\�����'�H(��-�\"�in]Ğ���\\�!�`M�H,gȎ�*�Kf�*\0�>6���6��2�hJ�7�{nq�8����H�#c�H�#�\r�:��7�8�܀Z��ZrD��߲`rG\0�l\n�I��i\0<����\0Lg�~���E��\$��P�\$�@�PƼT03�HGH�l�Q%*\"N?�%��	��\n�CrW�C\$��p�%�uR`��%��R\$�<�`�Ifx���\$/\$�����\$���O�(���\0��\0�RY�*�/	�\rܜC9��&hh�=I�'\$�RRI�'\\�a=E����u·'̙wI�'T���������K9%�d����!��������j�����&���v̟�\\=<,�E��`�Y��\\����*b0>�r��,d�pd���0DD ̖`�,T �1�% P���/�\r�b�(���J����T0�``ƾ����J�t���ʟ((d�ʪ�h+ <Ɉ+H%i�����#�`� ���'��B>t��J�Z\\�`<J�+hR���8�hR�,J]g�I��0\n%J�*�Y���JwD��&ʖD�������R�K\"�1Q�� ��AJKC,�mV�������-���KI*�r��\0�L�\"�Kb(����J:qKr�d�ʟ-)��ˆ#Ը�޸[�A�@�.[�Ҩʼ�4���.�1�J�.̮�u#J���g\0��򑧣<�&���K�+�	M?�/d��%'/��2Y��>�\$��l�\0��+����}-t��ͅ*�R�\$ߔ��K�.����JH�ʉ�2\r��B���(P���6\"��nf�\0#Ї ��%\$��[�\n�no�LJ�����e'<����1K��y�Y1��s�0�&zLf#�Ƴ/%y-�ˣ3-��K��L�΁��0����[,��̵,������0���(�.D��@��2�L+.|�����2�(�L�*��S:\0�3����G3l��aːl�@L�3z4�ǽ%̒�L�3����!0�33=L�4|ȗ��+\"���4���7�,\$�SPM�\\��?J�Y�̡��+(�a=K��4���C̤<Ё�=\$�,��UJ]5h�W�&t�I%��5�ҳ\\M38g�́5H�N?W1H��^��Ը�Y͗ؠ�͏.�N3M�4Å�`��i/P�7�dM>�d�/�LR���=K�60>�I\0[��\0��\r2���Z@�1��2��7�9�FG+�Ҝ�\r)�hQtL}8\$�BeC#��r*H�۫�-�H�/���6��\$�RC9�ب!���7�k/P�0Xr5��3D���<T�Ԓq�K���n�H�<�F�:1SL�r�%(��u)�Xr�1��nJ�I��S�\$\$�.·9��IΟ�3 �L�l���Ι9��C�N�#ԡ�\$�/��s��9�@6�t���N�9���N�:����7�Ӭ�:D���M)<#���M}+�2�N��O&��JNy*���ٸ[;���O\"m����M�<c�´���8�K�,���N�=07s�JE=T��O<����J�=D��:�C<���ˉ=���K�ʻ̳�L3�����LTЀ3�S,�.���q-��s�7�>�?�7O;ܠ`�OA9���ϻ\$���O�;��`9�n�I�A�xp��E=O�<��5����2�O�?d�����`N�iO�>��3�P	?���O�m��S�M�ˬ��=�(�d�Aȭ9���\0�#��@��9D����&���?����i9�\n�/��A���ȭA��S�Po?kuN5�~4���6���=򖌓*@(�N\0\\۔dG��p#��>�0��\$2�4z )�`�W���+\0��80�菦������z\"T��0�:\0�\ne \$��rM�=�r\n�N�P�Cmt80�� #��J=�&��3\0*��B�6�\"������#��>�	�(Q\n���8�1C\rt2�EC�\n`(�x?j8N�\0��[��QN>���'\0�x	c���\n�3��Ch�`&\0���8�\0�\n���O`/����A`#��Xc���D �tR\n>���d�B�D�L��������Dt4���j�p�GAoQoG8,-s����K#�);�E5�TQ�G�4Ao\0�>�tM�D8yRG@'P�C�	�<P�C�\"�K\0��x��~\0�ei9���v))ѵGb6���H\r48�@�M�:��F�tQ�!H��{R} �URp���O\0�I�t8������[D4F�D�#��+D�'�M����>RgI���Q�J���U�)Em���TZ�E�'��iE����qFzA��>�)T�Q3H�#TL�qIjNT���&C��h�X\nT���K\0000�5���JH�\0�FE@'љFp�hS5F�\"�oѮ�e%aoS E)� ��DU��Q�Fm�ѣM��Ѳe(tn� �U1ܣ~>�\$��ǂ��(h�ǑG�y`�\0��	��G��3�5Sp(��P�G�\$��#��	���N�\n�V\$��]ԜP�=\"RӨ?Lzt��1L\$\0��G~��,�KN�=���GM����NS�)��O]:ԊS}�81�RGe@C�\0�OP�S�N�1��T!P�@��S����S�G`\n�:��P�j�7R� @3��\n� �������DӠ��L�����	��\0�Q5���CP��SMP�v4��?h	h�T�D0��֏��>&�ITx�O�?�@U��R8@%Ԗ��K���N�K��RyE�E#�� @����%L�Q�Q����?N5\0�R\0�ԁT�F�ԔR�S�!oTE�C(�����ĵ\0�?3i�SS@U�QeM��	K�\n4P�CeS��\0�NC�P��O�!�\"RT�����S�N���U5OU>UiI�PU#UnKP��UYT�*�C��U�/\0+���)��:ReA�\$\0���x��WD�3���`����U5�IHUY��:�P	�e\0�MJi�����Q�>�@�T�C{��u��?�^�v\0WR�]U}C��1-5+U�?�\r�W<�?5�JU-SX��L�� \\t�?�sM�b�ՃV܁t�T�>�MU+�	E�c���9Nm\rRǃC�8�S�X�'R��XjCI#G|�!Q�Gh�t�Q��� )<�Y�*��RmX0����M���OQ�Y�h���du���Z(�Ao#�NlyN�V�Z9I���M��V�ZuOՅT�T�EՇַS�e����\n�X��S�QER����[MF�V�O=/����>�gչT�V�oU�T�Z�N�*T\\*����S-p�S��V�q��M(�Q=\\�-UUUV�C���Z�\nu�V\$?M@U�WJ\r\rU��\\�'U�W]�W��W8�N�'#h=oC���F(��:9�Yu����V-U�9�]�C�:U�\\�\n�qW���(TT?5P�\$ R3�⺟C}`>\0�E]�#R��	��#R�)�W���:`#�G�)4�R��;��ViD%8�)Ǔ^�Q��#�h	�HX	��\$N�x��#i x�ԒXR��'�9`m\\���\nE��Q�`�bu@��N�dT�#YY����GV�]j5#?L�xt/#���#酽O�P��Q��6����^� �������M\\R5t�Ӛp�*��X�V\"W�D�	oRALm\rdG�N	����6�p\$�P废E5����Tx\n�+��C[��V�����8U�Du}ػF\$.��Q-;4Ȁ�NX\n�.X�b͐�\0�b�)�#�N�G4K��ZS�^״M�8��d�\"C��>��dHe\n�Y8���.� ���ҏF�D��W1cZ6��Q�KH�@*\0�^���\\Q�F�4U3Y|�=�Ӥ�E��ۤ�?-�47Y�Pm�hYw_\r�VeױM���ُe(0��F�\r�!�PUI�u�7Q�C�ю?0����gu\rqधY-Q�����=g\0�\0M#�U�S5Zt�֟ae^�\$>�ArV�_\r;t���HW�Z�@H��hzD��\0�S2J� HI�O�'ǁe�g�6�[�R�<�?� /��KM����\n>��H�Z!i����TX6���i�C !ӛg�� �G }Q6��4>�w�!ڙC}�VB�>�UQڑj�8c�U�T���'<�>����HC]�V��7jj3v���`0���23����x�@U�k�\n�:Si5��#Y�-w����M?c��MQ�GQ�уb`��\0�@��ҧ\0M��)ZrKX�֟�Wl������l�TM�D\r4�QsS�40�sQ́�mY�h�d��C`{�V�gE�\n��XkՁ�'��,4���^�6�#<4��NXnM):��OM_6d�������[\"KU�n��?l�x\0&\0�R56�T~>��ո?�Jn��� ��Z/i�6���glͦ�U��F}�.����JL�CTbM�4��cL�TjSD�}Jt���Z����:�L���d:�Ez�ʤ�>��V\$2>����[�p�6��R�9u�W.?�1��RHu���R�?58Ԯ��D��u���p�c�Z�?�r׻ Eaf��}5wY���ϒ���W�wT[Sp7'�_aEk�\"[/i��#�\$;m�fأWO����F�\r%\$�ju-t#<�!�\n:�KEA����]�\nU�Q�KE��#��X��5[�>�`/��D��֭VEp�)��I%�q���n�x):��le���[e�\\�eV[j�����7 -+��G�WEwt�WkE�~u�Q/m�#ԐW�`�yu�ǣD�A�'ױ\r��ՙO�D )ZM^��u-|v8]�g��h���L��W\0���6�X��=Y�d�Q�7ϓ��9����r <�֏�D��B`c�9���`�D�=wx�I%�,ᄬ�����j[њ����O��� ``��|�����������.�	AO���	��@�@ 0h2�\\�ЀM{e�9^>���@7\0��˂W���\$,��Ś�@؀����w^fm�,\0�yD,ם^X�.�ֆ�7����2��f;��6�\n����^�zC�קmz��n�^���&LFF�,��[��e��aXy9h�!:z�9c�Q9b� !���Gw_W�g�9���S+t���p�tɃ\nm+����_�	��\\���k5���]�4�_h�9 ��N����]%|��7�֜�];��|���X��9�|����G���[��\0�}U���MC�I:�qO�Vԃa\0\r�R�6π�\0�@H��P+r�S�W���p7�I~�p/��H�^������E�-%��̻�&.��+�Jђ;:���!���N�	�~����/�W��!�B�L+�\$��q�=��+�`/Ƅe�\\���x�pE�lpS�JS�ݢ��6��_�(ů���b\\O��&�\\�59�\0�9n���D�{�\$���K��v2	d]�v�C�����?�tf|W�:���p&��Ln��賞�{;���G�R9��T.y���I8���\rl� �	T��n�3���T.�9��3����Z�s����G����:	0���z��.�]��ģQ�?�gT�%��x�Ռ.����n<�-�8B˳,B��rgQ�����Ɏ`��2�:{�g��s��g�Z��� ׌<��w{���bU9�	`5`4�\0BxMp�8qnah�@ؼ�-�(�>S|0�����3�8h\0���C�zLQ�@�\n?��`A��>2��,���N�&��x�l8sah1�|�B�ɇD�xB�#V��V�׊`W�a'@���	X_?\n�  �_�. �P�r2�bUar�I�~��S���\0ׅ\"�2����>b;�vPh{[�7a`�\0�˲j�o�~���v��|fv�4[�\$��{�P\rv�BKGbp������O�5ݠ2\0j�لL���)�m��V�ejBB.'R{C��V'`؂ ��%�ǀ�\$�O��\0�`����4 �N�>;4���/�π��*��\\5���!��`X*�%��N�3S�AM���Ɣ,�1����\\��caϧ ��@��˃�B/����0`�v2��`hD�JO\$�@p!9�!�\n1�7pB,>8F4��f�π:��7���3��3����T8�=+~�n���\\�e�<br����Fز� ��C�N�:c�:�l�<\r��\\3�>���6�ONn��!;��@�tw�^F�L�;���,^a��\ra\"��ڮ'�:�v�Je4�א;��_d\r4\r�:����S�����2��[c��X�ʦPl�\$�ޣ�i�w�d#�B��b��������`:���~ <\0�2����R���P�\r�J8D�t@�E��\0\r͜6����7����Y���\"����\r�����3��.�+�z3�;_ʟvL����wJ�94�I�Ja,A����;�s?�N\nR��!��ݐ�Om�s�_��-zۭw���zܭ7���z���M����o����\0��a��ݹ4�8�Pf�Y�?��i��eB�S�1\0�jDTeK��UYS�?66R	�c�6Ry[c���5�]B͔�R�_eA)&�[凕XYRW�6VYaeU�fYe�w��U�b�w�E�ʆ;z�^W�9��ק�ݖ��\0<ޘ�e�9S���da�	�_-��L�8ǅ�Q��TH[!<p\0��Py5�|�#��P�	�9v��2�|Ǹ��fao��,j8�\$A@k����a���b�c��f4!4���cr,;�����b�=��;\0��ź���cd��X�b�x�a�Rx0A�h�+w�xN[��B��p���w�T�8T%��M�l2�������}��s.kY��0\$/�fU�=��s�gK���M� �?���`4c.��!�&�分g��f�/�f1�=��V AE<#̹�f\n�)���Np��`.\"\"�A�����q��X��٬:a�8��f��Vs�G��r�:�V��c�g�Vl��g=��`��W���y�gU��˙�Ẽ�eT=�����x 0� M�@����%κb���w��f��O�筘�*0���|t�%��P��p��gK���?p�@J�<Bٟ#�`1��9�2�g�!3~����nl��f��Vh���.����aC���?���-�1�68>A��a�\r��y�0��i�J�}�������z:\r�)�S���@��h@���Y���mCEg�cyφ��<���h@�@�zh<W��`��:zO���\r��W���V08�f7�(Gy���`St#��f�#����C(9���؀d���8T:���0�� q���79��phAg�6�.��7Fr�b� �j��A5��a1��h�ZCh:�%��gU��D9��Ɉ�׹��0~vTi;�VvS��w��\r΃?��f�����n�ϛiY��a��3�·9�,\n��r��,/,@.:�Y>&��F�)�����}�b���iO�i��:d�A�n��c=�L9O�h{�� 8hY.������������\r��և�����1Q�U	�C�h��e�O���+2o����N�����zp�(�]�h��Z|�O�c�zD���;�T\0j�\0�8#�>Ύ�=bZ8Fj���;�޺T酡w��)���N`���ÅB{��z\r�c���|dTG�i�/��!i��0���'`Z:�CH�(8�`V������\0�ꧩ��W��Ǫ��zgG������-[��	i��N\rq��n���o	ƥfEJ��apb��}6���=o���,t�Y+��EC\r�Px4=����@���.��F��[�zq���X6:FG��#��\$@&�ab��hE:����`�S�1�1g1���2uhY��_:Bߡdc�*���\0�ƗFYF�:���n���=ۨH*Z�Mhk�/�냡�zٹ]��h@����1\0��ZK�������^+�,vf�s��>���O�|���s�\0֜5�X��ѯF��n�A�r]|�Ii4�� ��C� h@ع����cߥ�6smO������gX�V2�6g?~��Y�Ѱ�s�cl \\R�\0��c��A+�1������\n(����^368cz:=z��(�� ;裨�s�F�@`;�,>yT��&��d�Lן��%��-�CHL8\r��b�����Mj]4�Ym9����Z�B��P}<���X���̥�+g�^�M� + B_Fd�X���l�w�~�\r⽋�\":��qA1X������3�ΓE�h�4�ZZ��&����1~!N�f��o���\nMe�଄��XI΄�G@V*X��;�Y5{V�\n���T�z\rF�3}m��p1�[�>�t�e�w����@V�z#��2��	i���{�9��p̝�gh���+[elU���A�ٶӼi1�!��omm�*K���}��!�Ƴ��{me�f`��m��C�z=�n�:}g� T�mLu1F��}=8�Z���O��mFFMf��OO����������/����ޓ���V�oqj���n!+����Z��I�.�9!nG�\\��3a�~�O+��::�K@�\n�@���Hph��\\B��dm�fvC���P�\" ��.nW&��n��HY�+\r���z�i>Mfqۤ��Qc�[�H+��o��*�1'��#āEw�D_X�)>�s��-~\rT=�������- �y�m����{�h��j�M�)�^����'@V�+i�������;F��D[�b!����B	��:MP���ۭoC�vAE?�C�IiY��#�p�P\$k�J�q�.�07���x�l�sC|���bo�2�X�>M�\rl&��:2�~��cQ����o��d�-��U�Ro�Y�nM;�n�#��\0�P�f��Po׿(C�v<���[�o۸����fѿ���;�ẖ�[�Y�.o�Up���pU���.���B!'\0���<T�:1�������<���n��F���I�ǔ��V0�ǁRO8�w��,aF��ɥ�[�Ο��YO����/\0��ox���Q�?��:ً���`h@:�����/M�m�x:۰c1������v�;���^���@��@�����\n{�����;���B��8�� g坒�\\*g�yC)��E�^�O�h	���A�u>���@�D��Y�����`o�<>��p���ķ�q,Y1Q��߸��/qg�\0+\0���D���?�� ����k:�\$����ץ6~I��=@���!��v�zO񁚲�+���9�i����a������g������?��0Gn�q�]{Ҹ,F���O���� <_>f+��,��	���&�����·�y�ǩO�:�U¯�L�\n�úI:2��-;_Ģ�|%�崿!��f�\$���Xr\"Kni����\$8#�g�t-��r@L�圏�@S�<�rN\n�D/rLdQk࣓�����e����Э��\n=4)�B���ך��Z-|Hb����Hk�*	�Q!�'��G ��Ybt!��(n,�P�Ofq�+X�Y����\"b F6��r f�\"�ܳ!N��^��r�B_(�\"�K�_-<��*Q���/,)�H\0����r�\"z2(�tه.F>��#3���268sh٠��ƑI1Sn20���-��4���2A�s(�4�˶��\0��#��r�K'�ͷG'�7&\n>x���J�GO8,�0���8���\0�W9��I�?:3n�\r-w:�����;3ȉ�!�;��ꃘ�Z�RM�+>�����0/=R�'1�4�8����m�%ȥ}χ9�;�=�nQ��=�hhL��G�kW�\r�	%�4Ҝs�ΖJ�3s�4�@�U�%\$���N;�?4���N��2|��Z�3�h\0�3�5�^�xi2d\r|�M�ʣbh|�#v�` \0�ꐮ���\$\r2h#���?���I\n���+o-��?6`ṽ�.\$���KY%�J?�c�R�N#K:�K�EL�>:��@��jP��n_t&slm�'�ЩɸӜ�����;6ۗHU5#�Q7U��WY�U bN��W�_���;TC�[�<ږ>����W�CU��6X#`MI:t�ӵ��	u#`�fu�\$�t���X�`�f<�;b�gh���9�7�S58���#^�-�\0����չR*�'��(���qZ壣�X�Q�FUv�W GW���T��W�~ڭ^�W�����J=_ؗbm��bV\\l��/�M��TmTOXu�=_��ITvvu�a\rL_�qR/]]m�su=H=u�g o\\UՅgM�	XVU��%�h��53U�\\=��Q��M�v���g�m��ue�����h�b�M�GCeO5�ԁ�O5��Y�i=e�	G�TURvOa�*�ivWX�J5<��bu�]������<����\$u3v#�'e�u�R5m��v�D5�.v���W=�U_�(�\\V��_<��S�n)�1M%Qh�Z�T�f5E�'��W��v�UmiՂU��]aW�U�dRv��-YUZu��UV��UiR�V������[��ZMU�\\=�v{�X���wQ�huHv��gqݴw!�oqt�U{TGq�{�#^G_ubQ���i9Qb>�NUd��k��5hP�mu[�\0����_��[�Y-����r���(�CrMe�J�!h?QrX3 x���#��x�<�{u5~���-�u��YyQ\r-��\0�uգuuٿpUڅ�)�P��\r<u�S�0��w��-i���!�֊�B���d]��Ň��E��vlmQݏ6k��J��w�Ğ����ED�U�R�e�v:X�c�NW}`-�t�H#e��b��u���	~B7� ?�	OP�CW���SE͕V>���U�7�����m�ӂ�z�=����1���+��m�I,>�X7��]�.��*	^��N��.��/\"���)�	���s��|��ӟ�l�}�����!�5n�p�j��h�}���m�E�zH�aO0d=A|w�߳������u���v���G�x#��b�cS�o-��tOm`C��^M��@�h�n\$k�`�`HD^�PE�[�]��rR�m�=�.�ه>Ayi� \"���	��o�-,.�\nq+���fXd����*߽�K�؃'�� �%a����9p���KLM��!�,������zX#�V�uH%!��63�J�ryՁ��q_�u	�W����|@3b1��7|~wﱳ��A7���	��9cS&{���%Vx��kZO��w�Ur?����N �|�C�#Ű��կ �/��9�ft�Ew�C��a�^\0�O<�W�{Y�=�e��n���gyf0h@�S�\0:C���^��VgpE9:85�3�ާ���@��j_�[�+��ǩx�^�ꮆ~@чW���㓜�9x�FC���.�����k^I���pU9��S������\$���\r4���\0��O���)L[�p?�.PECS�I1nm{�?�P�WA߲�;���D�;S�a�Kf��%�?�X��+��B>��9���Gj�c�z�A͎�:�a�n0bJ{o��!3��!'��K�����}�\\��3W��5�x���L;�2ζn�a;���׺Xӛ]�o��x�{�5ޙjX���vӚ��q��EE{р4����{���	�\n��>��aﯷ�����L����������'����{�\n��>J�ߌ��ӗ��Y�\rOʽ�t����-O���4��9F�;�����G��I�F��1�o����O���a{w�0����Ư;񔄑l�o��J�Tb\rw�2�J��=D#�n�:�y��S�^�,.�?(�I\$���Ư��3��s�4M�aCR���G̑��I߰n<�zy�XN��?��.��=���DǼ�\r����\n��\ro��\nПCl%��Y���߰��G���}#�VН%�(����3�ɍ�r��};��׿G��n�[�{����_<m4[	I����q��?�0cV�nms��nM���\"Nj1�w?@�\$1��>��^�����\\�{n�\\���7���ٟic1���hoo�?j<G�x�l���S�r}���|\"}��/�?s��tI���&^�1e��t��,�*'F��=�/F�k�,95rV������쑈��o9��/F��_�~*^��{�I����_�����^n���N��~���A�d����U�w�qY���T�2��G�?�&����:y��%��X�J�C�d	W�ߎ~�G!��J}��������B-��;���h�*�R���E��~���.�~���SAqDVx���='��E�(^���~����������o7~�M[��Q��(��y��nP�>[WX{q�aϤ���.&N�3]��HY������[���&�8?�3������݆����#���B�e�6��@��[������G\r�+��}������_��7�|N����4~(z�~����%��?����[��1�S�]x�k��KxO^�A���rZ+����*�W��k�wD(���R:��\0����'����m!O�\n��u���.�[ �P�!��}��m ��1p�u��,T��L 	0}��&P٥\n�=D�=���\rA/�o@��2�t�6�DK��\0���q�7�l���B���(�;[��kr\r�;#���lŔ\r�<}zb+��O�[�WrX�`�Z ţ�Pm'Fn����Sp�-�\0005�`d���P���Ǿ��;��n\0�5f�P���EJ�w�� �.?�;��N�ޥ,;Ʀ�-[7��e��i��-���dَ<[~�6k:&�.7�]�\0������/�59 ��@eT:煘�3�d�sݝ�5䏜5f\0�P��HB�����8J�LS\0vI\0���7Dm��a�3e��?B��\$�.E���f���@�n���b�Gb��q3�|��Paˈ�ϯX7Tg>�.�p�5��AHŵ��3S�,��@�#&w��3��m[���I�ѥ�^�̤J1?�gTၽ#�S�=_��_��	���Vq/C۾�݀�|�����D �g>܄��� 6\r�7}q��Ť�JG�B^�\\g������&%��[�2Ixì��6\03]�3�{�@RU��M��v<�1����sz�uP�5��F:�i�|�`�q���V| ��\nk��}�'|�gd�!�8� <,�P7�m��||���I�A��]BB �F�0X���	�D��`W���qm�OL�	�.�(�p��ҁ��\"!����\0��A����V��7k��M�\$�N0\\���\"�f������\0uq��,��5��A6�p���\n�ΐjY�7[pK��4;�l�5n��@�\\f��l	��M���P��3��C�HbЌ��cEpP���4eooe�{\r-��2.�֥��P50u���G}��\0����<\r��!��~�������\n7F��d�����>��a��%�c6Ԟ��M��|��d����O�_�?J��C0�>Ё�&7kM4�`%f�l�ΘB~�wx��ZG�P�2��0�=�*p��@�BeȔ��|2�\r�?q��8����Њ(�yr���0��>�>�E?w�|r]�%Av�����@�+�X��Ag����s��C��AXmNҝ�4\0\r���8J�J�ǸD�Қ�:=	������S�4��F;	�\\&��P!6%\$i�xi4c�0B�;62=��1��̈PC��m���dpc+�5��\$/rCR�`�MQ�6(\\��2A���\\��lG�l�\0Bq��P�r���B����т�_6Ll�!BQ��IG�����XRbs�]B�Hr���`�X��\$p�8���	nbR,±�L��\"�E%\0�aYB�s���D,�!��ϛpN9RbG�4��M��t����jU�����y\0��%\$.�iL!x��ғ�(�.�)6T(�I��a%�K�]m�t���&��G7�ITM�B�\rza��])va�%���41T�j͹(!�����\\�\\�W��\\t\$�0��%�\0aK\$�T�F(Y�C@��H���H�nD�d��Wp��hZ�'�ZC,/���\$����J�FB�uܬQ:Υ�A��:-a#��=jb��l�Ug;{R��U��EWn�Ua��V��Nj��u�G�*�yֹ%��@��*���Yx�_�z�]�)v\"��R��L�VIv�=`��'��U�) S\r~R���\ni��)5S��D49~�b�;)3�,�9M3�HsJkT�Ü�(����uJ�][\$uf��ob���\n.,�Yܵ9j1'��!�1�\$J��gڤ՟ĆU0��Zuah���cH��,�Yt��Kb�5��5��/dY��AU�҅��[W>�_V�\r��*���j��-T�� z�Y�d�c�m�ҹ��:����[Ut-{���l	�i+a)�.[��_:�5��h��W§�m��%JI��[T�h>�������;�X̺d�S�d�V�;\rƱ!N��K&�A�Ju4B��dg΢.Vp��mb��)�V!U\0G丨��`���\\��q�7Q�b�VL��:�Ղ���Z.�N��*�ԏU]Z�l�z������R D1I��£�r:\0<1~;#�Jb���M�y�+�۔/�\"ϛj<3�#��̌��:P.}�e����D\"q�yJ�G���sop�����X�\r��d��\rxJ%���ƼO:%yy��,��%{�3<�Xø����z�E�z(\0 �D_���.2+�g�b�c�x�pgި��|9CP����48U	Q�/Aq��Q�(4 7e\$D��v:�V�b��N4[��iv���2�\r�X1��AJ(<PlF�\0���\\z�)���W�(�4����� p�����`��\r�da6����O��m�a�}q�`��6P�'h��3�|����f� j��A�z���+�D�UW�D���5��%#�x�3{��L\r-͙]:jd�P	j�f�q:Z�\"sad�)�G�3	��+��r�NK��1Q���x=>�\"��-�:�F���Iك*�@ԟ�y�T�\\U��Y~������3D������f,s�8HV�'�t9v(:��B9�\\Z����(�&�E8���W\$X\0�\n��9�WB��b��66j9� �ʈ��?,��| �a��g1�\nPs�\0@�%#K����\r\0ŧ\0���0�?�š,�\0��h��h�\08\0l\0�-�Z��jb�Ŭ\0p\0�-�f`ql��0\0i-�\\ps��7�e\"-Z�lb�E�,�\0��]P ��E��b\0�/,Z��\r�\0000�[f-@\rӯEڋ�/�Z8��~\"��ڋ��.^��Qw��ϋ�\0�/t_ȼ���E���\0�0d]��b�Ť�|\0��\\ؼ���E�\0af0tZ��n�J�\0l\0�0L^��Qj@��J��^��q#F(�1�/�[�1�����I�.�^8��\0[�q��[Ñl\"�� ��\0�0,d����\r����c��{cE�\0o�0�]�\0\rc%�ۋ���8�w���Z��-�\\��{��֋G�/\\bp��@1�\0a�1�����s�!Ũ�/�/�]8��~c\"�ۋ��2�cΑm�\"�9�q�/\\^fQ~c�_���-\$i�\"�\0003����fX�qx#\09��Z.�i���@F���3tZH� \rcK�b\0j�/Dj��1����I�h�a��v�Ʃ�OZ4�Z��т#YE�\0i�.hH��sX/F<���.�j���b���\0mV/d\\���b�E����3T^(�шcKFR�����]X�q��������6�]h��c6Eċ�66�h����n\0005�sn/dn��`\r\"�F���-D`�Ց��N�2�Y��bx��#\\�닇V3x�1x�Fx��\0�6�b�q����!��8|^���ub�����-�r��q��:��%�0�pp�#����\0�6�f��Ǣ�Ŭ�d�0�qH����\$�@�q�-�^B4��\"�\08�1�/lnxϑ���G�3:0tjh�~@Ƽ���3�vH��b�G(�e��4gغq��2�1��-�nX��\"�F<�Q�1\\j��1���Eǋ��4m����[�n�z7�yh�1�#�ގ/�3\\x�q�KG����6�o��1{��FJ���6�lX�q⣄�u���9�r(�1��Gc\0�f:�rX��#�Ž\0i�<\\}���b�F�\0s�7�y2���#uFe��\">4i��������\n<{�㑍��Ɖ�J;�]��1�#��0��J;4^��D���Ǯ����4i��(H#��E�x�/�n��1��/ǡ��j6,l��1t�/\0005%�0�]x����GG5�!�0��������r�q�2��ޑ��NFP�o\"4�_��1�d�%�e �3�s8���G5�� �6�[H��c�H�jY�;�[辑�b�! �y�@�\\��q�#WHN���;�c�Q��:�-�%�.�kXƑ���G͌��1Df�ߑ�cWFl��!�0����c Eܐ��;l��q�\"�F����7\\\\������O�q�.T|\"?����E��f9TyYѩ�SG1���A\$f9R\n\"��x��>B��H��ߤ\0���:\$e�1���F?�=�3Tu)\nq�b��~���<T��α�c�H.�m~C�wHʱ�#/�I�]~3�^��ф#��>�Y�4�^��Qjc��K�1\"�8�|6��c\"�B��\"b4���%����G\0e\"�/t���1r�1��e!v2�y����<Ǡ���8\\o��ђ#t�ѐ\rz@�}H�b���y �1�\\���deG��Z3�~�r)�1ȿ���Bl~H��:�dF��-�?�k8�q�c(F͋�K�5|my�c1�<�*@�j���1��ž��>I�Z��Qj��2��\$0��h�Q��VFT�	\$�Al~�qڣȱ�\$�>\\p�\rq�\$/�u%�!�Jq \$��tE��GN-Tq)�\"��Hʌ��=�X�2-�H���8\\n��RW\$H��\"�C\\_�\0�d\$�f��\".D�u	'Q�zE��&0to��qj��ƿ��R@d������u�##�LLk�*q�\$*Gđi�@T�i�l��E����5���r\\d�I���\"/�Z�0�j\$T���z5Ld3�����o�.Tq�!1{�����9�Z��Q�b�F�wJ94n�����{�(�-�8�2h�u��;\$�-Dk��rs��H���#���Y7�\"�/E����	\$j�^�-�]�7�[\"N\$����W����/]�\$�+�1Ga�/&IDn�@\$��!��\$�-�k!�Q����)(N/\$t������O�KzP�tX��[\0�G��w(*K\$v��1�c�'��G̞I�xd��\n�A�8\\rX��a��I�iN�I%\$���_���6�f�Q�#��I�5#�F��غ��#�E⒕\"�3\$�I�c�H���vR|�Q��cE���:R�e��h�EΏfK`8�r.#�E��s�0L���R��F���!\nC\$`���\$�H?��nP�e�!�@F'���/�����������%�N,h��rF\$�����3�t��Ҁ���!1<��CQ�%�Ò��J�Z�f.�6ō����C���Ԝ.�[��Bҿx����\0NRn`���Y\n�%+N�IMs:ùYd�ef�B[���nƹY��m��R�ג��Y��C�X���j��U+Vk,�\0P��b@e���x��V��yT�7�u�[J�ȱ\nD��eR��mx&�l�\0)�}�J�,\0�I�ZƵ\$k!���Yb�����Re/Q���k�5.�e��5����W�`��\0)�Yv\"V�\0��\n�%��`Yn�աa��xÆQ!,�`\"�	_.�偩Ɩtm\$�\"��J��֍���v�%�M9j��	斧�*�Kp֔�;\\R ��3(���^��:}���|>µa-'U%w*�#>�@�̬e�J���;Pw/+��5E\rjn���d���^[���cΰ�u�z\\ؐ1mi\"x��p��;����P)����#��ؒ���!A�;��	4�a{`aV{K�U��8㨟0''o�2���yc̸9]K�@�җ^�lB��Or���,du��8�?����%�gB����Yn+�%c�e\0���ऱYr@f�(]ּ�\nbiz��n�SS2��GdBPj���@�(�ȥ�!�-�v��e�*c\0��4J�炒���,�U�	d��e�j'T�H]Ԋ�G!�)u��֯��ү�Z�B5�̓W��0\n���R���W��\\�Q j�^r�%l��3,�Yy��f3&��܎�Q:ϵ2�m�R)�T��(KR��0�ʔ@��Y��Y:��e3\r%���T�%�X����ST�.J\\�0�h�ą�D!�:�u���U\"�Ł�o+7�\"����f'��R\0���J��2S�2�#nm ��I劜�\"X���[�ր��} J��c�9p0���Q�(U\0�xDEW��.L��=<B�0+�)ZS V;�\\�I{�5I�A���,dW�u�5Ew\n\$%ҁ���2i_\$��+��O,����X��ՑJg&J��G��%\\J��b.��^L�T�Fl�薹]k#f@L�G�ĐT�ٗ��H��\"�q1S̰��j�V�(Ι��ZVz�ņ�,����G�.1F��gN�;�1ÊV��5E��5`�\0Ct�=F\nṛα�K����\0�ۊ�%��D]Q\$\r\0�3J\\,͙��<T4*���.�YK�D�Q��L�S%,�g������<��u0���Uĉ�*x(��NYv!��y�	w�4fd��rG��M \$��^;�����)<P�]D�%%�;�j��I0�a�u^Jp�[)�v�3RhR�E��\n�L_�#5|ܾ�m3P�*�\\Y51X��	i�N���\$\"��a���h*KU���V8��u�%&�r�˚��5o���g�;�rMl[ƨ�g������U�q�깚h|�eO2�f MlW2AP�׹�����v~eD�e�3Uӫl�E62i�����Ub���U���������V��iI!\$i�ʭ&Z:��xm!ņ�.�O�fwү!���kݤ̓��6b\"�I�J]]:T��6�Vr��}��ǫ]����U��	ys7f�Mř�3����Y��:T_M�w%3�n��\n��z*��3�h��	�`U��L���,�ۄ�5��vf��Û�42_Q��h���uD�\no��)�ĜիM9�7foۼ��r����WB~iT�eyQT�N\n�d�pr�#��M�;���4�p���t���(;���5	|��ǂ��',AV7ܔ��UA�&��R�P�\"��y�ҷ��)�[�n���-3V��,?�s6�p���3�f��A��9k|�ɮS�f�*@��5�g��ɿ2��}����U�ݙ����H�F�l%�p«Ie�be�M�SO\r�[��i�3�f��LV��r�u�����NA�:�%r��y3Q�_̸�W.���^Sl@&���5�Yl��1���}Vx�gʅ�^Sn���Q!:5�Z�iZCԈ:���3qg�%D��ݪ{U�3�tZ�`��u%w:�ZQ:Q���W f�훿9Jpl�)�3x�v���K7�b#�����X+J�(��h��P*Ӂ���Λ��!ה�ŏSL�h*'���\npB��ڪ�gNʝ�8BuҪ���Ό��8ni�I�s�US�I��;vvڳU�sR�7N�u�8�H|���ӷ�̎��8�q����+'���`�x�9R�	ծ��MaR8�x�)��'!���;�U��Y֓��sNI�g:�KT�y�3�g��Y����k���ܳn'LO(��3�w4�4������l���J����w��9�\\����hf(�_~���}9N���\0���b\"�Y餃Th,ڞ�@��D���\$�I��;�e��U��n����,�O��	X��g�-���+>ti'G����l�%\0�8�VB�U1�ye�\0KT�4���m��V2)\r]I/\rF���X���ߨ�a��G�¹�*�����>ER������Z�-)I\$����:�a�\0�Fyba�g�w��(�_@�v}�i�ʳ�S^�25DԳ�	��URO��JH��\\�is�f��K�N��qi�Sg�O\n�F~|���*@gR�_Q<9sܬ3i+ؗ�.Cw���|���y�6a�O�Y9���ɖ\n�Խ-([���_�}�S�]c�S=��������Y��U->�<���\n<�sO�Q4F�^}\0007u�k(/���/5{L�9�\0����&��[<���s�\0&��#�@h��3�V}��H���*�w+]'D�&�@�ց])��;TGe3��\\��n����d\$:�uN4�ykt�-dR!7����e4(P!��-��9�4�_PMGb��ıw����6O�S�F���)��yh0+����qT|��+u���+��A�?��	�T�3.q��41T��e��\n:P����{T�\n��h?��T�A�S��*���+�u�>�\\�Z����Y췢wEJ��%��s�L��d��y�+\rC�ߡ'A�l,�y�3���͗`�	_*�P� ThKDV���~5	�0�+�,�-?�]���3�֍K�`�^���I42(]�w�.�r����]�\nYƨB����	��}ЋR ��g�}:H��J�WP��\"޵���V\\�<��? >�����ܬ݆�=��:�\n0��\\+�S���f�U���U,�WCֈ�On��΅��.�e9|R�I'�[�/������2���Q��Bn:�I�\n��g�9�\r�,�R6����Q\$X�+�>����`\n�)/_8Qi�����=��v?5v�\0 \n���LG�Dm�w\\�F֌�Ѣ���dꟵ}s�\"��Yv�|�J*�9h���@XEU�*�(oQ]\$�B��,�����KT�v�AptCɃ\n�C,/�<��ڙEW�-V�P��=W�*%K�-Q`9	(��59Ӏ�m)�X��@�2���T@��\nS���bd�Eδa�+�DX��|U�	�	��F� 2�%5\nj�m��W�+�x�K��V�3#��CT�ek���&�,�l�jbd7)ӓ\"\n+�P��b��I�@�3��ܵjU��Es��)D�f뒃������P�Z3AΌ�\nwTh𗲪ۘ�4Z��<�uߩ�dq�ˊu(���bKG����n�Tﮈ]z��f%#�3I�fS��&}�@D�@++��A�h���\n��U�ޥ|B�;��Um��U�E�N�!�x2�1�\0�GmvH~��H�T�)�W��YN�\"�k5��vT#=�ڥ�<\n}�#R3Y�H�R�Iͳܦ;��Rl�1l�uB%TQJ�*���'�E�0i�dw,�z�ͥ:\$��;�?���j��)��)ԏ�\$32J}�&�[�\$��́�;Dn��E״�+0�aZ{���C ���(��:����O@h��D��\0��`PTou����F�\rQv����o�ܡ\$S��+��#7��Izr�pk�DW��Fs�9��Q� ���1�g��#�\0\\L�\$��3�g�X�y�y �-3h����!�nX��]+��	ɝ�c\0�\0�b��\0\r���-{�\0�Q(�Q�\$s�0���m(�[Ru�V����>��+�J[�6����J\0֗�\\���,��K�3�.�]a_\0R�J Ɨ`�^ԶClR�IK��\n�\$�nŏ���Kj��\n����~/��mn�].�`��ij��#K��f:`\0�錀6�7K▨zc��\0����/K���/�d���FE\0aL���dZ`�J�S��ʙ�2��4�@/�(��L��0�`�ĩ��_�L��]4Zh�Щ�SD�M��4:c��SR��M�E4�i��SG�EMj��4zd�թ�SFKL��%4�e��%\$�lKM2��1�ڔ�i����MV��.�ڔ�i����Lz�/���ۣӄ��M�,`�_��imS��gMƜ�jg�����5�9.��9j_��S���.��9�_���S���.�7�r�)��%�[2�m8�uT��S��3M:�]3�q���nӱ�KN�1|^�kt�\"��H�gKj�-;zc�i�Ӛ����\r<�_�-i�Ӹ��\"֞U.���i�RڑkOF��=:\\��\$Zө�MLE�5�x����ӻ_\"֜=<\0�t��S�9OҞ�1�~��i�����O��>�~q�)�F����=6:~���J���P:��=��T�)�ƫ��PJ8�@�w�����*��O�5]>��t���T\n��!\"��6Y	)��H�/P���3�	���/��P~���	�Ӯ�!\"��C����j� �eNJ������*%�4�1Q��CZ�Q�jTB�Q.�\rE)\0004��\$�2�SM+�<j�t�j0�,�9Q��}F\0\$�s��Ta��KΣ]Ecj*�'K�M��MGx��R�T1�#QꡥG��5�:�z�L��4u6z��\"j\"T�KuN֣�G�g\$jFSܨ�Q2��H��\"�MT��%R��Hz��\$�,�w�Re.\$r�z�)��Ԧ�-Q���J���ʪ@԰�=R&/�Iʕ1�*]T���7���Q��D&өqN�_(�q�c[Tw�QR�崜J�\0n��T���.��956c�܌�Sz�H���7�R�}�Sr8�N���\"b�T��Q�5MN���#����ES§-H��7\"�T��_S�}G�̕?*yԩ��S�P*�5#���܍�T:�]Pʟ�C*�ԉ�T:�-K8�5C����R�--MȾ�H��� �'T���H���H���ы�T���R���,���܋GTک-SJ��M*�ԩ�UTکmMH��M���>�gSD�5M�R���H�wU\"��K8��R���ڌ�U*�-U*��n¾T�IR�,t�Z���Y�IUF�51���W)v�k�_KƫpJ�5Zj�ů�R�4r\n�^jI�CK����}Uʓ_��ԛ��O�=N�R*�F-��R��%W���c��\\�aV>�EYj��d���ëUά�WX�5*�Ջ��Uy��Z��1k�ը�7V��R\\H�5h*�U���UƧM[���k�vո�3V�}[(�5W�zո�iB�O��1��T���V�;�[��pR�Gu�;T@0>\0��/I���W`�]��\0���8��P��]��1m*��ǍyUz�mW��|�ݓ[��֯�]J�ш��U������Z*�5\\j����Z��`Z�5~��E�W��4Z��5h�Q�^�cXZ��S��1o�V��U&��T��5}cU^��X��dm*���kUu��SfG=[��j�sտ��X�Kc\n�iR�H�i#��uWt��������X�cĹ��U���rڢ�UZ�Շ�NE���X���4��ud�E�eV^��K��n��V8�sX¥�f��/�hJ�-J]ӂ������zO��<Eh�\$勓���\0K��<bw��>���N�\")]b�	�+z�.cS.�iF�	���QNQ���V*������O[X�nx��P	k��oN��}<aO�Iߓ�h���T;�r񉉤�VD6Q�;z�]j�~'�:�[Iv��7^ʑ����j�w[������ņ�:u �Ds#���\\w�<n|*�h�m�Kv;Y҈��3�]��^#�Z�j�gy�jħY,�%;3������.�W\"��\$�3>gڜ���Ϧ�V�T�Zj�hY�j�kD*!�h&Xz�i���+GV��\"��Z�:Ҥ�+�NoG�Zjj�i�]ʞkO�_�֬ԐmjI����t��#�[�j\rn�����n��Z�_,���g�Ě�:���9����[L2�W=T��0��f�\0P�U6\ns%7isY�?��u�3���nb5�����X|G~l�&�k���M��������y�S��)�]�ܭr��ٸ�������?�}u'n0W-ι��b��Ǫ���k?�vQ�7��}p\n�����ٮZ*�9)��5ޕZW�-ZB���:��㫊W�\0WZfp�Gp���ٮ:�Fp����U��SN/��\\��%s9�S{� �8��Z�as�ۓ�+�N^��9�M�{�P5�� �Q���J���y����;����z����Y�V �3�:�D�I���+����19M;�������V���\rQ{��ծ���+��F�CLĹ�N���Ԉ�\\��)\$i���N'\0���P����]X�^�s1�f�&�\"'<O���̡�L\0�\"�@���%�6��UA�1�i(z��݁�\r�Ղ��bZ��+IQO�3���\r=*ĉ��)�!����`��h��,ЫmGPC��A��ٲ�A��(ZŰ%�t�,h/���i��k���XEJ6�ID�Ȭ\"�\n�aU- ��\nv�y��_���ګ�k	a�B<�V�D�/P���a��)9L�(Z��8�vvù�k	�o�ZXk���|�&�.�東C�����`�1�]7&ę+�H�CBcX�B7xX�|1��0��a�6��ubpJLǅ�(���mbl�8I�*R��@tk0�����xX���;�� al]4s�t��Ū�0�c�'��l�`8M�8����D4w`p?@706g̈~K�\r�� �P���bh�\"&��\n�q�PD����\$�(�0QP<�����Q�!X��x��5���R�`w/2�2#���� `���1�/�܁\r���:²����B7�V7Z��gMY�H3� ��b�	Z��J���G�w�gl�^�-�R-!�l�7̲L��ư<1 �QC/ղh��)�W�6C	�*d��6]VK!m����05G\$�R��4��=Cw&[��YP��dɚ�')VK,�5e�\r���K+�1�X)b�e)��uF2A#E�&g~�e�y�fp5�lYl�Ԝ5�����\n�m}`�(�M �Pl9Y��f����]�Vl-4�é����>`��/��fPE�i�\0k�v�\0�fhS0�&�¦lͼ�#fu���5	i%�:Fd��9��؀G<�	{�}��s[7\0�Ξ3�ft:+.Ȕ�p�>�ձ�@!Pas6q,���1bǬŋ�ZK���-��ar`�?RxX�鑡�V���#Ĥ�z�; �D���H��1��6D`��Y�`�R�P֋>-�!\$�����~π���`>���h�0�1����&\0�h���I�wl�Z�\$�\\\r��8�~,�\n�o_��B2D����a1��ǩ�=�v<�kF�p`�`�kBF�6� ����h��T T֎�	�@?dr�剀J�H@1�G�dn��w���%��JG��0b�Tf]m(�k�qg\\�������ш3vk'�^d��AX��~�W�Vs�*�ʱ�d��M����@?���}�6\\��m9<��i�ݧ��Ԭh�^s}�-�[K�s�q�b��-��OORm8\$�yw��##��@❷\0��ؤ 5F7����X\n��|J�/-S�W!f�� 0�,w��D4١RU�T������ZX�=�`�W\$@�ԥ(�XG��Ҋ��a>�*�Y���\n��\n��!�[mj���0,mu�W@ FX������=��(���b��<!\n\"��83�'��(R��\n>��@�W�r!L�H�k�\r�E\nW��\r��'FH�\$�����m���=�ۥ{LY��&���_\0����#�䔀[�9\0�\"��@8�iK���0�l���p\ng��'qbF��y�c�l@9�(#JU�ݲ�{io���.{�ͳ4�V́�VnF�x���z� Q�ޞ\$kSa~ʨ0s@���%�y@��5H��N�ͦ�@�x�#	ܫ /\\��?<hڂ���I�T��:�3�\n%��");
    } else {
        header("Content-Type: image/gif");
        switch ($_GET["file"]) {
            case"plus.gif":
                echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0!�����M��*)�o��) q��e���#��L�\0;";
                break;
            case"cross.gif":
                echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0#�����#\na�Fo~y�.�_wa��1�J�G�L�6]\0\0;";
                break;
            case"up.gif":
                echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0 �����MQN\n�}��a8�y�aŶ�\0��\0;";
                break;
            case"down.gif":
                echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0 �����M��*)�[W�\\��L&ٜƶ�\0��\0;";
                break;
            case"arrow.gif":
                echo "GIF89a\0\n\0�\0\0������!�\0\0\0,\0\0\0\0\0\n\0\0�i������Ӳ޻\0\0;";
                break;
        }
    }
    exit;
}
if ($_GET["script"] == "version") {
    $id = file_open_lock(get_temp_dir() . "/adminer.version");
    if ($id) file_write_unlock($id, serialize(array("signature" => $_POST["signature"], "version" => $_POST["version"])));
    exit;
}
global $b, $g, $n, $ec, $mc, $wc, $o, $kd, $qd, $ba, $Rd, $y, $ca, $me, $qf, $bg, $Ih, $vd, $pi, $vi, $U, $Ji, $ia;
if (!$_SERVER["REQUEST_URI"]) $_SERVER["REQUEST_URI"] = $_SERVER["ORIG_PATH_INFO"];
if (!strpos($_SERVER["REQUEST_URI"], '?') && $_SERVER["QUERY_STRING"] != "") $_SERVER["REQUEST_URI"] .= "?$_SERVER[QUERY_STRING]";
if ($_SERVER["HTTP_X_FORWARDED_PREFIX"]) $_SERVER["REQUEST_URI"] = $_SERVER["HTTP_X_FORWARDED_PREFIX"] . $_SERVER["REQUEST_URI"];
$ba = ($_SERVER["HTTPS"] && strcasecmp($_SERVER["HTTPS"], "off")) || ini_bool("session.cookie_secure");
@ini_set("session.use_trans_sid", false);
if (!defined("SID")) {
    session_cache_limiter("");
    session_name("adminer_sid");
    $Of = array(0, preg_replace('~\?.*~', '', $_SERVER["REQUEST_URI"]), "", $ba);
    if (version_compare(PHP_VERSION, '5.2.0') >= 0) $Of[] = true;
    call_user_func_array('session_set_cookie_params', $Of);
    session_start();
}
remove_slashes(array(&$_GET, &$_POST, &$_COOKIE), $Vc);
if (get_magic_quotes_runtime()) set_magic_quotes_runtime(false);
@set_time_limit(0);
@ini_set("zend.ze1_compatibility_mode", false);
@ini_set("precision", 15);
function
get_lang()
{
    return 'en';
}

function
lang($ui, $hf = null)
{
    if (is_array($ui)) {
        $eg = ($hf == 1 ? 0 : 1);
        $ui = $ui[$eg];
    }
    $ui = str_replace("%d", "%s", $ui);
    $hf = format_number($hf);
    return
        sprintf($ui, $hf);
}

if (extension_loaded('pdo')) {
    class
    Min_PDO
        extends
        PDO
    {
        var $_result, $server_info, $affected_rows, $errno, $error;

        function
        __construct()
        {
            global $b;
            $eg = array_search("SQL", $b->operators);
            if ($eg !== false) unset($b->operators[$eg]);
        }

        function
        dsn($jc, $V, $F, $yf = array())
        {
            try {
                parent::__construct($jc, $V, $F, $yf);
            } catch (Exception$Ac) {
                auth_error(h($Ac->getMessage()));
            }
            $this->setAttribute(13, array('Min_PDOStatement'));
            $this->server_info = @$this->getAttribute(4);
        }

        function
        query($G, $Di = false)
        {
            $H = parent::query($G);
            $this->error = "";
            if (!$H) {
                list(, $this->errno, $this->error) = $this->errorInfo();
                if (!$this->error) $this->error = 'Unknown error.';
                return
                    false;
            }
            $this->store_result($H);
            return $H;
        }

        function
        multi_query($G)
        {
            return $this->_result = $this->query($G);
        }

        function
        store_result($H = null)
        {
            if (!$H) {
                $H = $this->_result;
                if (!$H) return
                    false;
            }
            if ($H->columnCount()) {
                $H->num_rows = $H->rowCount();
                return $H;
            }
            $this->affected_rows = $H->rowCount();
            return
                true;
        }

        function
        next_result()
        {
            if (!$this->_result) return
                false;
            $this->_result->_offset = 0;
            return @$this->_result->nextRowset();
        }

        function
        result($G, $p = 0)
        {
            $H = $this->query($G);
            if (!$H) return
                false;
            $J = $H->fetch();
            return $J[$p];
        }
    }

    class
    Min_PDOStatement
        extends
        PDOStatement
    {
        var $_offset = 0, $num_rows;

        function
        fetch_assoc()
        {
            return $this->fetch(2);
        }

        function
        fetch_row()
        {
            return $this->fetch(3);
        }

        function
        fetch_field()
        {
            $J = (object)$this->getColumnMeta($this->_offset++);
            $J->orgtable = $J->table;
            $J->orgname = $J->name;
            $J->charsetnr = (in_array("blob", (array)$J->flags) ? 63 : 0);
            return $J;
        }
    }
}
$ec = array();

class
Min_SQL
{
    var $_conn;

    function
    __construct($g)
    {
        $this->_conn = $g;
    }

    function
    select($Q, $L, $Z, $nd, $_f = array(), $_ = 1, $E = 0, $mg = false)
    {
        global $b, $y;
        $Yd = (count($nd) < count($L));
        $G = $b->selectQueryBuild($L, $Z, $nd, $_f, $_, $E);
        if (!$G) $G = "SELECT" . limit(($_GET["page"] != "last" && $_ != "" && $nd && $Yd && $y == "sql" ? "SQL_CALC_FOUND_ROWS " : "") . implode(", ", $L) . "\nFROM " . table($Q), ($Z ? "\nWHERE " . implode(" AND ", $Z) : "") . ($nd && $Yd ? "\nGROUP BY " . implode(", ", $nd) : "") . ($_f ? "\nORDER BY " . implode(", ", $_f) : ""), ($_ != "" ? +$_ : null), ($E ? $_ * $E : 0), "\n");
        $Dh = microtime(true);
        $I = $this->_conn->query($G);
        if ($mg) echo $b->selectQuery($G, $Dh, !$I);
        return $I;
    }

    function
    delete($Q, $wg, $_ = 0)
    {
        $G = "FROM " . table($Q);
        return
            queries("DELETE" . ($_ ? limit1($Q, $G, $wg) : " $G$wg"));
    }

    function
    update($Q, $O, $wg, $_ = 0, $M = "\n")
    {
        $Wi = array();
        foreach ($O
                 as $z => $X) $Wi[] = "$z = $X";
        $G = table($Q) . " SET$M" . implode(",$M", $Wi);
        return
            queries("UPDATE" . ($_ ? limit1($Q, $G, $wg, $M) : " $G$wg"));
    }

    function
    insert($Q, $O)
    {
        return
            queries("INSERT INTO " . table($Q) . ($O ? " (" . implode(", ", array_keys($O)) . ")\nVALUES (" . implode(", ", $O) . ")" : " DEFAULT VALUES"));
    }

    function
    insertUpdate($Q, $K, $kg)
    {
        return
            false;
    }

    function
    begin()
    {
        return
            queries("BEGIN");
    }

    function
    commit()
    {
        return
            queries("COMMIT");
    }

    function
    rollback()
    {
        return
            queries("ROLLBACK");
    }

    function
    slowQuery($G, $gi)
    {
    }

    function
    convertSearch($v, $X, $p)
    {
        return $v;
    }

    function
    value($X, $p)
    {
        return (method_exists($this->_conn, 'value') ? $this->_conn->value($X, $p) : (is_resource($X) ? stream_get_contents($X) : $X));
    }

    function
    quoteBinary($Yg)
    {
        return
            q($Yg);
    }

    function
    warnings()
    {
        return '';
    }

    function
    tableHelp($C)
    {
    }
}

$ec["sqlite"] = "SQLite 3";
$ec["sqlite2"] = "SQLite 2";
if (isset($_GET["sqlite"]) || isset($_GET["sqlite2"])) {
    $hg = array((isset($_GET["sqlite"]) ? "SQLite3" : "SQLite"), "PDO_SQLite");
    define("DRIVER", (isset($_GET["sqlite"]) ? "sqlite" : "sqlite2"));
    if (class_exists(isset($_GET["sqlite"]) ? "SQLite3" : "SQLiteDatabase")) {
        if (isset($_GET["sqlite"])) {
            class
            Min_SQLite
            {
                var $extension = "SQLite3", $server_info, $affected_rows, $errno, $error, $_link;

                function
                __construct($Uc)
                {
                    $this->_link = new
                    SQLite3($Uc);
                    $Zi = $this->_link->version();
                    $this->server_info = $Zi["versionString"];
                }

                function
                query($G)
                {
                    $H = @$this->_link->query($G);
                    $this->error = "";
                    if (!$H) {
                        $this->errno = $this->_link->lastErrorCode();
                        $this->error = $this->_link->lastErrorMsg();
                        return
                            false;
                    } elseif ($H->numColumns()) return
                        new
                        Min_Result($H);
                    $this->affected_rows = $this->_link->changes();
                    return
                        true;
                }

                function
                quote($P)
                {
                    return (is_utf8($P) ? "'" . $this->_link->escapeString($P) . "'" : "x'" . reset(unpack('H*', $P)) . "'");
                }

                function
                store_result()
                {
                    return $this->_result;
                }

                function
                result($G, $p = 0)
                {
                    $H = $this->query($G);
                    if (!is_object($H)) return
                        false;
                    $J = $H->_result->fetchArray();
                    return $J[$p];
                }
            }

            class
            Min_Result
            {
                var $_result, $_offset = 0, $num_rows;

                function
                __construct($H)
                {
                    $this->_result = $H;
                }

                function
                fetch_assoc()
                {
                    return $this->_result->fetchArray(SQLITE3_ASSOC);
                }

                function
                fetch_row()
                {
                    return $this->_result->fetchArray(SQLITE3_NUM);
                }

                function
                fetch_field()
                {
                    $e = $this->_offset++;
                    $T = $this->_result->columnType($e);
                    return (object)array("name" => $this->_result->columnName($e), "type" => $T, "charsetnr" => ($T == SQLITE3_BLOB ? 63 : 0),);
                }

                function
                __desctruct()
                {
                    return $this->_result->finalize();
                }
            }
        } else {
            class
            Min_SQLite
            {
                var $extension = "SQLite", $server_info, $affected_rows, $error, $_link;

                function
                __construct($Uc)
                {
                    $this->server_info = sqlite_libversion();
                    $this->_link = new
                    SQLiteDatabase($Uc);
                }

                function
                query($G, $Di = false)
                {
                    $Re = ($Di ? "unbufferedQuery" : "query");
                    $H = @$this->_link->$Re($G, SQLITE_BOTH, $o);
                    $this->error = "";
                    if (!$H) {
                        $this->error = $o;
                        return
                            false;
                    } elseif ($H === true) {
                        $this->affected_rows = $this->changes();
                        return
                            true;
                    }
                    return
                        new
                        Min_Result($H);
                }

                function
                quote($P)
                {
                    return "'" . sqlite_escape_string($P) . "'";
                }

                function
                store_result()
                {
                    return $this->_result;
                }

                function
                result($G, $p = 0)
                {
                    $H = $this->query($G);
                    if (!is_object($H)) return
                        false;
                    $J = $H->_result->fetch();
                    return $J[$p];
                }
            }

            class
            Min_Result
            {
                var $_result, $_offset = 0, $num_rows;

                function
                __construct($H)
                {
                    $this->_result = $H;
                    if (method_exists($H, 'numRows')) $this->num_rows = $H->numRows();
                }

                function
                fetch_assoc()
                {
                    $J = $this->_result->fetch(SQLITE_ASSOC);
                    if (!$J) return
                        false;
                    $I = array();
                    foreach ($J
                             as $z => $X) $I[($z[0] == '"' ? idf_unescape($z) : $z)] = $X;
                    return $I;
                }

                function
                fetch_row()
                {
                    return $this->_result->fetch(SQLITE_NUM);
                }

                function
                fetch_field()
                {
                    $C = $this->_result->fieldName($this->_offset++);
                    $ag = '(\[.*]|"(?:[^"]|"")*"|(.+))';
                    if (preg_match("~^($ag\\.)?$ag\$~", $C, $B)) {
                        $Q = ($B[3] != "" ? $B[3] : idf_unescape($B[2]));
                        $C = ($B[5] != "" ? $B[5] : idf_unescape($B[4]));
                    }
                    return (object)array("name" => $C, "orgname" => $C, "orgtable" => $Q,);
                }
            }
        }
    } elseif (extension_loaded("pdo_sqlite")) {
        class
        Min_SQLite
            extends
            Min_PDO
        {
            var $extension = "PDO_SQLite";

            function
            __construct($Uc)
            {
                $this->dsn(DRIVER . ":$Uc", "", "");
            }
        }
    }
    if (class_exists("Min_SQLite")) {
        class
        Min_DB
            extends
            Min_SQLite
        {
            function
            __construct()
            {
                parent::__construct(":memory:");
                $this->query("PRAGMA foreign_keys = 1");
            }

            function
            select_db($Uc)
            {
                if (is_readable($Uc) && $this->query("ATTACH " . $this->quote(preg_match("~(^[/\\\\]|:)~", $Uc) ? $Uc : dirname($_SERVER["SCRIPT_FILENAME"]) . "/$Uc") . " AS a")) {
                    parent::__construct($Uc);
                    $this->query("PRAGMA foreign_keys = 1");
                    return
                        true;
                }
                return
                    false;
            }

            function
            multi_query($G)
            {
                return $this->_result = $this->query($G);
            }

            function
            next_result()
            {
                return
                    false;
            }
        }
    }

    class
    Min_Driver
        extends
        Min_SQL
    {
        function
        insertUpdate($Q, $K, $kg)
        {
            $Wi = array();
            foreach ($K
                     as $O) $Wi[] = "(" . implode(", ", $O) . ")";
            return
                queries("REPLACE INTO " . table($Q) . " (" . implode(", ", array_keys(reset($K))) . ") VALUES\n" . implode(",\n", $Wi));
        }

        function
        tableHelp($C)
        {
            if ($C == "sqlite_sequence") return "fileformat2.html#seqtab";
            if ($C == "sqlite_master") return "fileformat2.html#$C";
        }
    }

    function
    idf_escape($v)
    {
        return '"' . str_replace('"', '""', $v) . '"';
    }

    function
    table($v)
    {
        return
            idf_escape($v);
    }

    function
    connect()
    {
        global $b;
        list(, , $F) = $b->credentials();
        if ($F != "") return 'Database does not support password.';
        return
            new
            Min_DB;
    }

    function
    get_databases()
    {
        return
            array();
    }

    function
    limit($G, $Z, $_, $D = 0, $M = " ")
    {
        return " $G$Z" . ($_ !== null ? $M . "LIMIT $_" . ($D ? " OFFSET $D" : "") : "");
    }

    function
    limit1($Q, $G, $Z, $M = "\n")
    {
        global $g;
        return (preg_match('~^INTO~', $G) || $g->result("SELECT sqlite_compileoption_used('ENABLE_UPDATE_DELETE_LIMIT')") ? limit($G, $Z, 1, 0, $M) : " $G WHERE rowid = (SELECT rowid FROM " . table($Q) . $Z . $M . "LIMIT 1)");
    }

    function
    db_collation($m, $pb)
    {
        global $g;
        return $g->result("PRAGMA encoding");
    }

    function
    engines()
    {
        return
            array();
    }

    function
    logged_user()
    {
        return
            get_current_user();
    }

    function
    tables_list()
    {
        return
            get_key_vals("SELECT name, type FROM sqlite_master WHERE type IN ('table', 'view') ORDER BY (name = 'sqlite_sequence'), name");
    }

    function
    count_tables($l)
    {
        return
            array();
    }

    function
    table_status($C = "")
    {
        global $g;
        $I = array();
        foreach (get_rows("SELECT name AS Name, type AS Engine, 'rowid' AS Oid, '' AS Auto_increment FROM sqlite_master WHERE type IN ('table', 'view') " . ($C != "" ? "AND name = " . q($C) : "ORDER BY name")) as $J) {
            $J["Rows"] = $g->result("SELECT COUNT(*) FROM " . idf_escape($J["Name"]));
            $I[$J["Name"]] = $J;
        }
        foreach (get_rows("SELECT * FROM sqlite_sequence", null, "") as $J) $I[$J["name"]]["Auto_increment"] = $J["seq"];
        return ($C != "" ? $I[$C] : $I);
    }

    function
    is_view($R)
    {
        return $R["Engine"] == "view";
    }

    function
    fk_support($R)
    {
        global $g;
        return !$g->result("SELECT sqlite_compileoption_used('OMIT_FOREIGN_KEY')");
    }

    function
    fields($Q)
    {
        global $g;
        $I = array();
        $kg = "";
        foreach (get_rows("PRAGMA table_info(" . table($Q) . ")") as $J) {
            $C = $J["name"];
            $T = strtolower($J["type"]);
            $Sb = $J["dflt_value"];
            $I[$C] = array("field" => $C, "type" => (preg_match('~int~i', $T) ? "integer" : (preg_match('~char|clob|text~i', $T) ? "text" : (preg_match('~blob~i', $T) ? "blob" : (preg_match('~real|floa|doub~i', $T) ? "real" : "numeric")))), "full_type" => $T, "default" => (preg_match("~'(.*)'~", $Sb, $B) ? str_replace("''", "'", $B[1]) : ($Sb == "NULL" ? null : $Sb)), "null" => !$J["notnull"], "privileges" => array("select" => 1, "insert" => 1, "update" => 1), "primary" => $J["pk"],);
            if ($J["pk"]) {
                if ($kg != "") $I[$kg]["auto_increment"] = false; elseif (preg_match('~^integer$~i', $T)) $I[$C]["auto_increment"] = true;
                $kg = $C;
            }
        }
        $zh = $g->result("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = " . q($Q));
        preg_match_all('~(("[^"]*+")+|[a-z0-9_]+)\s+text\s+COLLATE\s+(\'[^\']+\'|\S+)~i', $zh, $De, PREG_SET_ORDER);
        foreach ($De
                 as $B) {
            $C = str_replace('""', '"', preg_replace('~^"|"$~', '', $B[1]));
            if ($I[$C]) $I[$C]["collation"] = trim($B[3], "'");
        }
        return $I;
    }

    function
    indexes($Q, $h = null)
    {
        global $g;
        if (!is_object($h)) $h = $g;
        $I = array();
        $zh = $h->result("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = " . q($Q));
        if (preg_match('~\bPRIMARY\s+KEY\s*\((([^)"]+|"[^"]*"|`[^`]*`)++)~i', $zh, $B)) {
            $I[""] = array("type" => "PRIMARY", "columns" => array(), "lengths" => array(), "descs" => array());
            preg_match_all('~((("[^"]*+")+|(?:`[^`]*+`)+)|(\S+))(\s+(ASC|DESC))?(,\s*|$)~i', $B[1], $De, PREG_SET_ORDER);
            foreach ($De
                     as $B) {
                $I[""]["columns"][] = idf_unescape($B[2]) . $B[4];
                $I[""]["descs"][] = (preg_match('~DESC~i', $B[5]) ? '1' : null);
            }
        }
        if (!$I) {
            foreach (fields($Q) as $C => $p) {
                if ($p["primary"]) $I[""] = array("type" => "PRIMARY", "columns" => array($C), "lengths" => array(), "descs" => array(null));
            }
        }
        $Bh = get_key_vals("SELECT name, sql FROM sqlite_master WHERE type = 'index' AND tbl_name = " . q($Q), $h);
        foreach (get_rows("PRAGMA index_list(" . table($Q) . ")", $h) as $J) {
            $C = $J["name"];
            $w = array("type" => ($J["unique"] ? "UNIQUE" : "INDEX"));
            $w["lengths"] = array();
            $w["descs"] = array();
            foreach (get_rows("PRAGMA index_info(" . idf_escape($C) . ")", $h) as $Xg) {
                $w["columns"][] = $Xg["name"];
                $w["descs"][] = null;
            }
            if (preg_match('~^CREATE( UNIQUE)? INDEX ' . preg_quote(idf_escape($C) . ' ON ' . idf_escape($Q), '~') . ' \((.*)\)$~i', $Bh[$C], $Hg)) {
                preg_match_all('/("[^"]*+")+( DESC)?/', $Hg[2], $De);
                foreach ($De[2] as $z => $X) {
                    if ($X) $w["descs"][$z] = '1';
                }
            }
            if (!$I[""] || $w["type"] != "UNIQUE" || $w["columns"] != $I[""]["columns"] || $w["descs"] != $I[""]["descs"] || !preg_match("~^sqlite_~", $C)) $I[$C] = $w;
        }
        return $I;
    }

    function
    foreign_keys($Q)
    {
        $I = array();
        foreach (get_rows("PRAGMA foreign_key_list(" . table($Q) . ")") as $J) {
            $r =& $I[$J["id"]];
            if (!$r) $r = $J;
            $r["source"][] = $J["from"];
            $r["target"][] = $J["to"];
        }
        return $I;
    }

    function
    view($C)
    {
        global $g;
        return
            array("select" => preg_replace('~^(?:[^`"[]+|`[^`]*`|"[^"]*")* AS\s+~iU', '', $g->result("SELECT sql FROM sqlite_master WHERE name = " . q($C))));
    }

    function
    collations()
    {
        return (isset($_GET["create"]) ? get_vals("PRAGMA collation_list", 1) : array());
    }

    function
    information_schema($m)
    {
        return
            false;
    }

    function
    error()
    {
        global $g;
        return
            h($g->error);
    }

    function
    check_sqlite_name($C)
    {
        global $g;
        $Kc = "db|sdb|sqlite";
        if (!preg_match("~^[^\\0]*\\.($Kc)\$~", $C)) {
            $g->error = sprintf('Please use one of the extensions %s.', str_replace("|", ", ", $Kc));
            return
                false;
        }
        return
            true;
    }

    function
    create_database($m, $d)
    {
        global $g;
        if (file_exists($m)) {
            $g->error = 'File exists.';
            return
                false;
        }
        if (!check_sqlite_name($m)) return
            false;
        try {
            $A = new
            Min_SQLite($m);
        } catch (Exception$Ac) {
            $g->error = $Ac->getMessage();
            return
                false;
        }
        $A->query('PRAGMA encoding = "UTF-8"');
        $A->query('CREATE TABLE adminer (i)');
        $A->query('DROP TABLE adminer');
        return
            true;
    }

    function
    drop_databases($l)
    {
        global $g;
        $g->__construct(":memory:");
        foreach ($l
                 as $m) {
            if (!@unlink($m)) {
                $g->error = 'File exists.';
                return
                    false;
            }
        }
        return
            true;
    }

    function
    rename_database($C, $d)
    {
        global $g;
        if (!check_sqlite_name($C)) return
            false;
        $g->__construct(":memory:");
        $g->error = 'File exists.';
        return @rename(DB, $C);
    }

    function
    auto_increment()
    {
        return " PRIMARY KEY" . (DRIVER == "sqlite" ? " AUTOINCREMENT" : "");
    }

    function
    alter_table($Q, $C, $q, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        global $g;
        $Pi = ($Q == "" || $cd);
        foreach ($q
                 as $p) {
            if ($p[0] != "" || !$p[1] || $p[2]) {
                $Pi = true;
                break;
            }
        }
        $c = array();
        $If = array();
        foreach ($q
                 as $p) {
            if ($p[1]) {
                $c[] = ($Pi ? $p[1] : "ADD " . implode($p[1]));
                if ($p[0] != "") $If[$p[0]] = $p[1][0];
            }
        }
        if (!$Pi) {
            foreach ($c
                     as $X) {
                if (!queries("ALTER TABLE " . table($Q) . " $X")) return
                    false;
            }
            if ($Q != $C && !queries("ALTER TABLE " . table($Q) . " RENAME TO " . table($C))) return
                false;
        } elseif (!recreate_table($Q, $C, $c, $If, $cd, $Ma)) return
            false;
        if ($Ma) {
            queries("BEGIN");
            queries("UPDATE sqlite_sequence SET seq = $Ma WHERE name = " . q($C));
            if (!$g->affected_rows) queries("INSERT INTO sqlite_sequence (name, seq) VALUES (" . q($C) . ", $Ma)");
            queries("COMMIT");
        }
        return
            true;
    }

    function
    recreate_table($Q, $C, $q, $If, $cd, $Ma, $x = array())
    {
        global $g;
        if ($Q != "") {
            if (!$q) {
                foreach (fields($Q) as $z => $p) {
                    if ($x) $p["auto_increment"] = 0;
                    $q[] = process_field($p, $p);
                    $If[$z] = idf_escape($z);
                }
            }
            $lg = false;
            foreach ($q
                     as $p) {
                if ($p[6]) $lg = true;
            }
            $hc = array();
            foreach ($x
                     as $z => $X) {
                if ($X[2] == "DROP") {
                    $hc[$X[1]] = true;
                    unset($x[$z]);
                }
            }
            foreach (indexes($Q) as $ge => $w) {
                $f = array();
                foreach ($w["columns"] as $z => $e) {
                    if (!$If[$e]) continue
                    2;
                    $f[] = $If[$e] . ($w["descs"][$z] ? " DESC" : "");
                }
                if (!$hc[$ge]) {
                    if ($w["type"] != "PRIMARY" || !$lg) $x[] = array($w["type"], $ge, $f);
                }
            }
            foreach ($x
                     as $z => $X) {
                if ($X[0] == "PRIMARY") {
                    unset($x[$z]);
                    $cd[] = "  PRIMARY KEY (" . implode(", ", $X[2]) . ")";
                }
            }
            foreach (foreign_keys($Q) as $ge => $r) {
                foreach ($r["source"] as $z => $e) {
                    if (!$If[$e]) continue
                    2;
                    $r["source"][$z] = idf_unescape($If[$e]);
                }
                if (!isset($cd[" $ge"])) $cd[] = " " . format_foreign_key($r);
            }
            queries("BEGIN");
        }
        foreach ($q
                 as $z => $p) $q[$z] = "  " . implode($p);
        $q = array_merge($q, array_filter($cd));
        $ai = ($Q == $C ? "adminer_$C" : $C);
        if (!queries("CREATE TABLE " . table($ai) . " (\n" . implode(",\n", $q) . "\n)")) return
            false;
        if ($Q != "") {
            if ($If && !queries("INSERT INTO " . table($ai) . " (" . implode(", ", $If) . ") SELECT " . implode(", ", array_map('idf_escape', array_keys($If))) . " FROM " . table($Q))) return
                false;
            $Ai = array();
            foreach (triggers($Q) as $zi => $hi) {
                $yi = trigger($zi);
                $Ai[] = "CREATE TRIGGER " . idf_escape($zi) . " " . implode(" ", $hi) . " ON " . table($C) . "\n$yi[Statement]";
            }
            $Ma = $Ma ? 0 : $g->result("SELECT seq FROM sqlite_sequence WHERE name = " . q($Q));
            if (!queries("DROP TABLE " . table($Q)) || ($Q == $C && !queries("ALTER TABLE " . table($ai) . " RENAME TO " . table($C))) || !alter_indexes($C, $x)) return
                false;
            if ($Ma) queries("UPDATE sqlite_sequence SET seq = $Ma WHERE name = " . q($C));
            foreach ($Ai
                     as $yi) {
                if (!queries($yi)) return
                    false;
            }
            queries("COMMIT");
        }
        return
            true;
    }

    function
    index_sql($Q, $T, $C, $f)
    {
        return "CREATE $T " . ($T != "INDEX" ? "INDEX " : "") . idf_escape($C != "" ? $C : uniqid($Q . "_")) . " ON " . table($Q) . " $f";
    }

    function
    alter_indexes($Q, $c)
    {
        foreach ($c
                 as $kg) {
            if ($kg[0] == "PRIMARY") return
                recreate_table($Q, $Q, array(), array(), array(), 0, $c);
        }
        foreach (array_reverse($c) as $X) {
            if (!queries($X[2] == "DROP" ? "DROP INDEX " . idf_escape($X[1]) : index_sql($Q, $X[0], $X[1], "(" . implode(", ", $X[2]) . ")"))) return
                false;
        }
        return
            true;
    }

    function
    truncate_tables($S)
    {
        return
            apply_queries("DELETE FROM", $S);
    }

    function
    drop_views($bj)
    {
        return
            apply_queries("DROP VIEW", $bj);
    }

    function
    drop_tables($S)
    {
        return
            apply_queries("DROP TABLE", $S);
    }

    function
    move_tables($S, $bj, $Yh)
    {
        return
            false;
    }

    function
    trigger($C)
    {
        global $g;
        if ($C == "") return
            array("Statement" => "BEGIN\n\t;\nEND");
        $v = '(?:[^`"\s]+|`[^`]*`|"[^"]*")+';
        $_i = trigger_options();
        preg_match("~^CREATE\\s+TRIGGER\\s*$v\\s*(" . implode("|", $_i["Timing"]) . ")\\s+([a-z]+)(?:\\s+OF\\s+($v))?\\s+ON\\s*$v\\s*(?:FOR\\s+EACH\\s+ROW\\s)?(.*)~is", $g->result("SELECT sql FROM sqlite_master WHERE type = 'trigger' AND name = " . q($C)), $B);
        $jf = $B[3];
        return
            array("Timing" => strtoupper($B[1]), "Event" => strtoupper($B[2]) . ($jf ? " OF" : ""), "Of" => ($jf[0] == '`' || $jf[0] == '"' ? idf_unescape($jf) : $jf), "Trigger" => $C, "Statement" => $B[4],);
    }

    function
    triggers($Q)
    {
        $I = array();
        $_i = trigger_options();
        foreach (get_rows("SELECT * FROM sqlite_master WHERE type = 'trigger' AND tbl_name = " . q($Q)) as $J) {
            preg_match('~^CREATE\s+TRIGGER\s*(?:[^`"\s]+|`[^`]*`|"[^"]*")+\s*(' . implode("|", $_i["Timing"]) . ')\s*(.*?)\s+ON\b~i', $J["sql"], $B);
            $I[$J["name"]] = array($B[1], $B[2]);
        }
        return $I;
    }

    function
    trigger_options()
    {
        return
            array("Timing" => array("BEFORE", "AFTER", "INSTEAD OF"), "Event" => array("INSERT", "UPDATE", "UPDATE OF", "DELETE"), "Type" => array("FOR EACH ROW"),);
    }

    function
    begin()
    {
        return
            queries("BEGIN");
    }

    function
    last_id()
    {
        global $g;
        return $g->result("SELECT LAST_INSERT_ROWID()");
    }

    function
    explain($g, $G)
    {
        return $g->query("EXPLAIN QUERY PLAN $G");
    }

    function
    found_rows($R, $Z)
    {
    }

    function
    types()
    {
        return
            array();
    }

    function
    schemas()
    {
        return
            array();
    }

    function
    get_schema()
    {
        return "";
    }

    function
    set_schema($bh)
    {
        return
            true;
    }

    function
    create_sql($Q, $Ma, $Jh)
    {
        global $g;
        $I = $g->result("SELECT sql FROM sqlite_master WHERE type IN ('table', 'view') AND name = " . q($Q));
        foreach (indexes($Q) as $C => $w) {
            if ($C == '') continue;
            $I .= ";\n\n" . index_sql($Q, $w['type'], $C, "(" . implode(", ", array_map('idf_escape', $w['columns'])) . ")");
        }
        return $I;
    }

    function
    truncate_sql($Q)
    {
        return "DELETE FROM " . table($Q);
    }

    function
    use_sql($k)
    {
    }

    function
    trigger_sql($Q)
    {
        return
            implode(get_vals("SELECT sql || ';;\n' FROM sqlite_master WHERE type = 'trigger' AND tbl_name = " . q($Q)));
    }

    function
    show_variables()
    {
        global $g;
        $I = array();
        foreach (array("auto_vacuum", "cache_size", "count_changes", "default_cache_size", "empty_result_callbacks", "encoding", "foreign_keys", "full_column_names", "fullfsync", "journal_mode", "journal_size_limit", "legacy_file_format", "locking_mode", "page_size", "max_page_count", "read_uncommitted", "recursive_triggers", "reverse_unordered_selects", "secure_delete", "short_column_names", "synchronous", "temp_store", "temp_store_directory", "schema_version", "integrity_check", "quick_check") as $z) $I[$z] = $g->result("PRAGMA $z");
        return $I;
    }

    function
    show_status()
    {
        $I = array();
        foreach (get_vals("PRAGMA compile_options") as $xf) {
            list($z, $X) = explode("=", $xf, 2);
            $I[$z] = $X;
        }
        return $I;
    }

    function
    convert_field($p)
    {
    }

    function
    unconvert_field($p, $I)
    {
        return $I;
    }

    function
    support($Pc)
    {
        return
            preg_match('~^(columns|database|drop_col|dump|indexes|descidx|move_col|sql|status|table|trigger|variables|view|view_trigger)$~', $Pc);
    }

    $y = "sqlite";
    $U = array("integer" => 0, "real" => 0, "numeric" => 0, "text" => 0, "blob" => 0);
    $Ih = array_keys($U);
    $Ji = array();
    $vf = array("=", "<", ">", "<=", ">=", "!=", "LIKE", "LIKE %%", "IN", "IS NULL", "NOT LIKE", "NOT IN", "IS NOT NULL", "SQL");
    $kd = array("hex", "length", "lower", "round", "unixepoch", "upper");
    $qd = array("avg", "count", "count distinct", "group_concat", "max", "min", "sum");
    $mc = array(array(), array("integer|real|numeric" => "+/-", "text" => "||",));
}
$ec["pgsql"] = "PostgreSQL";
if (isset($_GET["pgsql"])) {
    $hg = array("PgSQL", "PDO_PgSQL");
    define("DRIVER", "pgsql");
    if (extension_loaded("pgsql")) {
        class
        Min_DB
        {
            var $extension = "PgSQL", $_link, $_result, $_string, $_database = true, $server_info, $affected_rows, $error, $timeout;

            function
            _error($xc, $o)
            {
                if (ini_bool("html_errors")) $o = html_entity_decode(strip_tags($o));
                $o = preg_replace('~^[^:]*: ~', '', $o);
                $this->error = $o;
            }

            function
            connect($N, $V, $F)
            {
                global $b;
                $m = $b->database();
                set_error_handler(array($this, '_error'));
                $this->_string = "host='" . str_replace(":", "' port='", addcslashes($N, "'\\")) . "' user='" . addcslashes($V, "'\\") . "' password='" . addcslashes($F, "'\\") . "'";
                $this->_link = @pg_connect("$this->_string dbname='" . ($m != "" ? addcslashes($m, "'\\") : "postgres") . "'", PGSQL_CONNECT_FORCE_NEW);
                if (!$this->_link && $m != "") {
                    $this->_database = false;
                    $this->_link = @pg_connect("$this->_string dbname='postgres'", PGSQL_CONNECT_FORCE_NEW);
                }
                restore_error_handler();
                if ($this->_link) {
                    $Zi = pg_version($this->_link);
                    $this->server_info = $Zi["server"];
                    pg_set_client_encoding($this->_link, "UTF8");
                }
                return (bool)$this->_link;
            }

            function
            quote($P)
            {
                return "'" . pg_escape_string($this->_link, $P) . "'";
            }

            function
            value($X, $p)
            {
                return ($p["type"] == "bytea" ? pg_unescape_bytea($X) : $X);
            }

            function
            quoteBinary($P)
            {
                return "'" . pg_escape_bytea($this->_link, $P) . "'";
            }

            function
            select_db($k)
            {
                global $b;
                if ($k == $b->database()) return $this->_database;
                $I = @pg_connect("$this->_string dbname='" . addcslashes($k, "'\\") . "'", PGSQL_CONNECT_FORCE_NEW);
                if ($I) $this->_link = $I;
                return $I;
            }

            function
            close()
            {
                $this->_link = @pg_connect("$this->_string dbname='postgres'");
            }

            function
            query($G, $Di = false)
            {
                $H = @pg_query($this->_link, $G);
                $this->error = "";
                if (!$H) {
                    $this->error = pg_last_error($this->_link);
                    $I = false;
                } elseif (!pg_num_fields($H)) {
                    $this->affected_rows = pg_affected_rows($H);
                    $I = true;
                } else$I = new
                Min_Result($H);
                if ($this->timeout) {
                    $this->timeout = 0;
                    $this->query("RESET statement_timeout");
                }
                return $I;
            }

            function
            multi_query($G)
            {
                return $this->_result = $this->query($G);
            }

            function
            store_result()
            {
                return $this->_result;
            }

            function
            next_result()
            {
                return
                    false;
            }

            function
            result($G, $p = 0)
            {
                $H = $this->query($G);
                if (!$H || !$H->num_rows) return
                    false;
                return
                    pg_fetch_result($H->_result, 0, $p);
            }

            function
            warnings()
            {
                return
                    h(pg_last_notice($this->_link));
            }
        }

        class
        Min_Result
        {
            var $_result, $_offset = 0, $num_rows;

            function
            __construct($H)
            {
                $this->_result = $H;
                $this->num_rows = pg_num_rows($H);
            }

            function
            fetch_assoc()
            {
                return
                    pg_fetch_assoc($this->_result);
            }

            function
            fetch_row()
            {
                return
                    pg_fetch_row($this->_result);
            }

            function
            fetch_field()
            {
                $e = $this->_offset++;
                $I = new
                stdClass;
                if (function_exists('pg_field_table')) $I->orgtable = pg_field_table($this->_result, $e);
                $I->name = pg_field_name($this->_result, $e);
                $I->orgname = $I->name;
                $I->type = pg_field_type($this->_result, $e);
                $I->charsetnr = ($I->type == "bytea" ? 63 : 0);
                return $I;
            }

            function
            __destruct()
            {
                pg_free_result($this->_result);
            }
        }
    } elseif (extension_loaded("pdo_pgsql")) {
        class
        Min_DB
            extends
            Min_PDO
        {
            var $extension = "PDO_PgSQL", $timeout;

            function
            connect($N, $V, $F)
            {
                global $b;
                $m = $b->database();
                $P = "pgsql:host='" . str_replace(":", "' port='", addcslashes($N, "'\\")) . "' options='-c client_encoding=utf8'";
                $this->dsn("$P dbname='" . ($m != "" ? addcslashes($m, "'\\") : "postgres") . "'", $V, $F);
                return
                    true;
            }

            function
            select_db($k)
            {
                global $b;
                return ($b->database() == $k);
            }

            function
            quoteBinary($Yg)
            {
                return
                    q($Yg);
            }

            function
            query($G, $Di = false)
            {
                $I = parent::query($G, $Di);
                if ($this->timeout) {
                    $this->timeout = 0;
                    parent::query("RESET statement_timeout");
                }
                return $I;
            }

            function
            warnings()
            {
                return '';
            }

            function
            close()
            {
            }
        }
    }

    class
    Min_Driver
        extends
        Min_SQL
    {
        function
        insertUpdate($Q, $K, $kg)
        {
            global $g;
            foreach ($K
                     as $O) {
                $Ki = array();
                $Z = array();
                foreach ($O
                         as $z => $X) {
                    $Ki[] = "$z = $X";
                    if (isset($kg[idf_unescape($z)])) $Z[] = "$z = $X";
                }
                if (!(($Z && queries("UPDATE " . table($Q) . " SET " . implode(", ", $Ki) . " WHERE " . implode(" AND ", $Z)) && $g->affected_rows) || queries("INSERT INTO " . table($Q) . " (" . implode(", ", array_keys($O)) . ") VALUES (" . implode(", ", $O) . ")"))) return
                    false;
            }
            return
                true;
        }

        function
        slowQuery($G, $gi)
        {
            $this->_conn->query("SET statement_timeout = " . (1000 * $gi));
            $this->_conn->timeout = 1000 * $gi;
            return $G;
        }

        function
        convertSearch($v, $X, $p)
        {
            return (preg_match('~char|text' . (!preg_match('~LIKE~', $X["op"]) ? '|date|time(stamp)?|boolean|uuid|' . number_type() : '') . '~', $p["type"]) ? $v : "CAST($v AS text)");
        }

        function
        quoteBinary($Yg)
        {
            return $this->_conn->quoteBinary($Yg);
        }

        function
        warnings()
        {
            return $this->_conn->warnings();
        }

        function
        tableHelp($C)
        {
            $we = array("information_schema" => "infoschema", "pg_catalog" => "catalog",);
            $A = $we[$_GET["ns"]];
            if ($A) return "$A-" . str_replace("_", "-", $C) . ".html";
        }
    }

    function
    idf_escape($v)
    {
        return '"' . str_replace('"', '""', $v) . '"';
    }

    function
    table($v)
    {
        return
            idf_escape($v);
    }

    function
    connect()
    {
        global $b, $U, $Ih;
        $g = new
        Min_DB;
        $j = $b->credentials();
        if ($g->connect($j[0], $j[1], $j[2])) {
            if (min_version(9, 0, $g)) {
                $g->query("SET application_name = 'Adminer'");
                if (min_version(9.2, 0, $g)) {
                    $Ih['Strings'][] = "json";
                    $U["json"] = 4294967295;
                    if (min_version(9.4, 0, $g)) {
                        $Ih['Strings'][] = "jsonb";
                        $U["jsonb"] = 4294967295;
                    }
                }
            }
            return $g;
        }
        return $g->error;
    }

    function
    get_databases()
    {
        return
            get_vals("SELECT datname FROM pg_database WHERE has_database_privilege(datname, 'CONNECT') ORDER BY datname");
    }

    function
    limit($G, $Z, $_, $D = 0, $M = " ")
    {
        return " $G$Z" . ($_ !== null ? $M . "LIMIT $_" . ($D ? " OFFSET $D" : "") : "");
    }

    function
    limit1($Q, $G, $Z, $M = "\n")
    {
        return (preg_match('~^INTO~', $G) ? limit($G, $Z, 1, 0, $M) : " $G" . (is_view(table_status1($Q)) ? $Z : " WHERE ctid = (SELECT ctid FROM " . table($Q) . $Z . $M . "LIMIT 1)"));
    }

    function
    db_collation($m, $pb)
    {
        global $g;
        return $g->result("SHOW LC_COLLATE");
    }

    function
    engines()
    {
        return
            array();
    }

    function
    logged_user()
    {
        global $g;
        return $g->result("SELECT user");
    }

    function
    tables_list()
    {
        $G = "SELECT table_name, table_type FROM information_schema.tables WHERE table_schema = current_schema()";
        if (support('materializedview')) $G .= "
UNION ALL
SELECT matviewname, 'MATERIALIZED VIEW'
FROM pg_matviews
WHERE schemaname = current_schema()";
        $G .= "
ORDER BY 1";
        return
            get_key_vals($G);
    }

    function
    count_tables($l)
    {
        return
            array();
    }

    function
    table_status($C = "")
    {
        $I = array();
        foreach (get_rows("SELECT c.relname AS \"Name\", CASE c.relkind WHEN 'r' THEN 'table' WHEN 'm' THEN 'materialized view' ELSE 'view' END AS \"Engine\", pg_relation_size(c.oid) AS \"Data_length\", pg_total_relation_size(c.oid) - pg_relation_size(c.oid) AS \"Index_length\", obj_description(c.oid, 'pg_class') AS \"Comment\", " . (min_version(12) ? "''" : "CASE WHEN c.relhasoids THEN 'oid' ELSE '' END") . " AS \"Oid\", c.reltuples as \"Rows\", n.nspname
FROM pg_class c
JOIN pg_namespace n ON(n.nspname = current_schema() AND n.oid = c.relnamespace)
WHERE relkind IN ('r', 'm', 'v', 'f')
" . ($C != "" ? "AND relname = " . q($C) : "ORDER BY relname")) as $J) $I[$J["Name"]] = $J;
        return ($C != "" ? $I[$C] : $I);
    }

    function
    is_view($R)
    {
        return
            in_array($R["Engine"], array("view", "materialized view"));
    }

    function
    fk_support($R)
    {
        return
            true;
    }

    function
    fields($Q)
    {
        $I = array();
        $Ca = array('timestamp without time zone' => 'timestamp', 'timestamp with time zone' => 'timestamptz',);
        $Dd = min_version(10) ? "(a.attidentity = 'd')::int" : '0';
        foreach (get_rows("SELECT a.attname AS field, format_type(a.atttypid, a.atttypmod) AS full_type, d.adsrc AS default, a.attnotnull::int, col_description(c.oid, a.attnum) AS comment, $Dd AS identity
FROM pg_class c
JOIN pg_namespace n ON c.relnamespace = n.oid
JOIN pg_attribute a ON c.oid = a.attrelid
LEFT JOIN pg_attrdef d ON c.oid = d.adrelid AND a.attnum = d.adnum
WHERE c.relname = " . q($Q) . "
AND n.nspname = current_schema()
AND NOT a.attisdropped
AND a.attnum > 0
ORDER BY a.attnum") as $J) {
            preg_match('~([^([]+)(\((.*)\))?([a-z ]+)?((\[[0-9]*])*)$~', $J["full_type"], $B);
            list(, $T, $te, $J["length"], $wa, $Fa) = $B;
            $J["length"] .= $Fa;
            $eb = $T . $wa;
            if (isset($Ca[$eb])) {
                $J["type"] = $Ca[$eb];
                $J["full_type"] = $J["type"] . $te . $Fa;
            } else {
                $J["type"] = $T;
                $J["full_type"] = $J["type"] . $te . $wa . $Fa;
            }
            if ($J['identity']) $J['default'] = 'GENERATED BY DEFAULT AS IDENTITY';
            $J["null"] = !$J["attnotnull"];
            $J["auto_increment"] = $J['identity'] || preg_match('~^nextval\(~i', $J["default"]);
            $J["privileges"] = array("insert" => 1, "select" => 1, "update" => 1);
            if (preg_match('~(.+)::[^)]+(.*)~', $J["default"], $B)) $J["default"] = ($B[1] == "NULL" ? null : (($B[1][0] == "'" ? idf_unescape($B[1]) : $B[1]) . $B[2]));
            $I[$J["field"]] = $J;
        }
        return $I;
    }

    function
    indexes($Q, $h = null)
    {
        global $g;
        if (!is_object($h)) $h = $g;
        $I = array();
        $Rh = $h->result("SELECT oid FROM pg_class WHERE relnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema()) AND relname = " . q($Q));
        $f = get_key_vals("SELECT attnum, attname FROM pg_attribute WHERE attrelid = $Rh AND attnum > 0", $h);
        foreach (get_rows("SELECT relname, indisunique::int, indisprimary::int, indkey, indoption , (indpred IS NOT NULL)::int as indispartial FROM pg_index i, pg_class ci WHERE i.indrelid = $Rh AND ci.oid = i.indexrelid", $h) as $J) {
            $Ig = $J["relname"];
            $I[$Ig]["type"] = ($J["indispartial"] ? "INDEX" : ($J["indisprimary"] ? "PRIMARY" : ($J["indisunique"] ? "UNIQUE" : "INDEX")));
            $I[$Ig]["columns"] = array();
            foreach (explode(" ", $J["indkey"]) as $Nd) $I[$Ig]["columns"][] = $f[$Nd];
            $I[$Ig]["descs"] = array();
            foreach (explode(" ", $J["indoption"]) as $Od) $I[$Ig]["descs"][] = ($Od & 1 ? '1' : null);
            $I[$Ig]["lengths"] = array();
        }
        return $I;
    }

    function
    foreign_keys($Q)
    {
        global $qf;
        $I = array();
        foreach (get_rows("SELECT conname, condeferrable::int AS deferrable, pg_get_constraintdef(oid) AS definition
FROM pg_constraint
WHERE conrelid = (SELECT pc.oid FROM pg_class AS pc INNER JOIN pg_namespace AS pn ON (pn.oid = pc.relnamespace) WHERE pc.relname = " . q($Q) . " AND pn.nspname = current_schema())
AND contype = 'f'::char
ORDER BY conkey, conname") as $J) {
            if (preg_match('~FOREIGN KEY\s*\((.+)\)\s*REFERENCES (.+)\((.+)\)(.*)$~iA', $J['definition'], $B)) {
                $J['source'] = array_map('trim', explode(',', $B[1]));
                if (preg_match('~^(("([^"]|"")+"|[^"]+)\.)?"?("([^"]|"")+"|[^"]+)$~', $B[2], $Ce)) {
                    $J['ns'] = str_replace('""', '"', preg_replace('~^"(.+)"$~', '\1', $Ce[2]));
                    $J['table'] = str_replace('""', '"', preg_replace('~^"(.+)"$~', '\1', $Ce[4]));
                }
                $J['target'] = array_map('trim', explode(',', $B[3]));
                $J['on_delete'] = (preg_match("~ON DELETE ($qf)~", $B[4], $Ce) ? $Ce[1] : 'NO ACTION');
                $J['on_update'] = (preg_match("~ON UPDATE ($qf)~", $B[4], $Ce) ? $Ce[1] : 'NO ACTION');
                $I[$J['conname']] = $J;
            }
        }
        return $I;
    }

    function
    view($C)
    {
        global $g;
        return
            array("select" => trim($g->result("SELECT pg_get_viewdef(" . $g->result("SELECT oid FROM pg_class WHERE relname = " . q($C)) . ")")));
    }

    function
    collations()
    {
        return
            array();
    }

    function
    information_schema($m)
    {
        return ($m == "information_schema");
    }

    function
    error()
    {
        global $g;
        $I = h($g->error);
        if (preg_match('~^(.*\n)?([^\n]*)\n( *)\^(\n.*)?$~s', $I, $B)) $I = $B[1] . preg_replace('~((?:[^&]|&[^;]*;){' . strlen($B[3]) . '})(.*)~', '\1<b>\2</b>', $B[2]) . $B[4];
        return
            nl_br($I);
    }

    function
    create_database($m, $d)
    {
        return
            queries("CREATE DATABASE " . idf_escape($m) . ($d ? " ENCODING " . idf_escape($d) : ""));
    }

    function
    drop_databases($l)
    {
        global $g;
        $g->close();
        return
            apply_queries("DROP DATABASE", $l, 'idf_escape');
    }

    function
    rename_database($C, $d)
    {
        return
            queries("ALTER DATABASE " . idf_escape(DB) . " RENAME TO " . idf_escape($C));
    }

    function
    auto_increment()
    {
        return "";
    }

    function
    alter_table($Q, $C, $q, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        $c = array();
        $vg = array();
        if ($Q != "" && $Q != $C) $vg[] = "ALTER TABLE " . table($Q) . " RENAME TO " . table($C);
        foreach ($q
                 as $p) {
            $e = idf_escape($p[0]);
            $X = $p[1];
            if (!$X) $c[] = "DROP $e"; else {
                $Vi = $X[5];
                unset($X[5]);
                if (isset($X[6]) && $p[0] == "") $X[1] = ($X[1] == "bigint" ? " big" : " ") . "serial";
                if ($p[0] == "") $c[] = ($Q != "" ? "ADD " : "  ") . implode($X); else {
                    if ($e != $X[0]) $vg[] = "ALTER TABLE " . table($C) . " RENAME $e TO $X[0]";
                    $c[] = "ALTER $e TYPE$X[1]";
                    if (!$X[6]) {
                        $c[] = "ALTER $e " . ($X[3] ? "SET$X[3]" : "DROP DEFAULT");
                        $c[] = "ALTER $e " . ($X[2] == " NULL" ? "DROP NOT" : "SET") . $X[2];
                    }
                }
                if ($p[0] != "" || $Vi != "") $vg[] = "COMMENT ON COLUMN " . table($C) . ".$X[0] IS " . ($Vi != "" ? substr($Vi, 9) : "''");
            }
        }
        $c = array_merge($c, $cd);
        if ($Q == "") array_unshift($vg, "CREATE TABLE " . table($C) . " (\n" . implode(",\n", $c) . "\n)"); elseif ($c) array_unshift($vg, "ALTER TABLE " . table($Q) . "\n" . implode(",\n", $c));
        if ($Q != "" || $ub != "") $vg[] = "COMMENT ON TABLE " . table($C) . " IS " . q($ub);
        if ($Ma != "") {
        }
        foreach ($vg
                 as $G) {
            if (!queries($G)) return
                false;
        }
        return
            true;
    }

    function
    alter_indexes($Q, $c)
    {
        $i = array();
        $fc = array();
        $vg = array();
        foreach ($c
                 as $X) {
            if ($X[0] != "INDEX") $i[] = ($X[2] == "DROP" ? "\nDROP CONSTRAINT " . idf_escape($X[1]) : "\nADD" . ($X[1] != "" ? " CONSTRAINT " . idf_escape($X[1]) : "") . " $X[0] " . ($X[0] == "PRIMARY" ? "KEY " : "") . "(" . implode(", ", $X[2]) . ")"); elseif ($X[2] == "DROP") $fc[] = idf_escape($X[1]);
            else$vg[] = "CREATE INDEX " . idf_escape($X[1] != "" ? $X[1] : uniqid($Q . "_")) . " ON " . table($Q) . " (" . implode(", ", $X[2]) . ")";
        }
        if ($i) array_unshift($vg, "ALTER TABLE " . table($Q) . implode(",", $i));
        if ($fc) array_unshift($vg, "DROP INDEX " . implode(", ", $fc));
        foreach ($vg
                 as $G) {
            if (!queries($G)) return
                false;
        }
        return
            true;
    }

    function
    truncate_tables($S)
    {
        return
            queries("TRUNCATE " . implode(", ", array_map('table', $S)));
        return
            true;
    }

    function
    drop_views($bj)
    {
        return
            drop_tables($bj);
    }

    function
    drop_tables($S)
    {
        foreach ($S
                 as $Q) {
            $Fh = table_status($Q);
            if (!queries("DROP " . strtoupper($Fh["Engine"]) . " " . table($Q))) return
                false;
        }
        return
            true;
    }

    function
    move_tables($S, $bj, $Yh)
    {
        foreach (array_merge($S, $bj) as $Q) {
            $Fh = table_status($Q);
            if (!queries("ALTER " . strtoupper($Fh["Engine"]) . " " . table($Q) . " SET SCHEMA " . idf_escape($Yh))) return
                false;
        }
        return
            true;
    }

    function
    trigger($C, $Q = null)
    {
        if ($C == "") return
            array("Statement" => "EXECUTE PROCEDURE ()");
        if ($Q === null) $Q = $_GET['trigger'];
        $K = get_rows('SELECT t.trigger_name AS "Trigger", t.action_timing AS "Timing", (SELECT STRING_AGG(event_manipulation, \' OR \') FROM information_schema.triggers WHERE event_object_table = t.event_object_table AND trigger_name = t.trigger_name ) AS "Events", t.event_manipulation AS "Event", \'FOR EACH \' || t.action_orientation AS "Type", t.action_statement AS "Statement" FROM information_schema.triggers t WHERE t.event_object_table = ' . q($Q) . ' AND t.trigger_name = ' . q($C));
        return
            reset($K);
    }

    function
    triggers($Q)
    {
        $I = array();
        foreach (get_rows("SELECT * FROM information_schema.triggers WHERE event_object_table = " . q($Q)) as $J) $I[$J["trigger_name"]] = array($J["action_timing"], $J["event_manipulation"]);
        return $I;
    }

    function
    trigger_options()
    {
        return
            array("Timing" => array("BEFORE", "AFTER"), "Event" => array("INSERT", "UPDATE", "DELETE"), "Type" => array("FOR EACH ROW", "FOR EACH STATEMENT"),);
    }

    function
    routine($C, $T)
    {
        $K = get_rows('SELECT routine_definition AS definition, LOWER(external_language) AS language, *
FROM information_schema.routines
WHERE routine_schema = current_schema() AND specific_name = ' . q($C));
        $I = $K[0];
        $I["returns"] = array("type" => $I["type_udt_name"]);
        $I["fields"] = get_rows('SELECT parameter_name AS field, data_type AS type, character_maximum_length AS length, parameter_mode AS inout
FROM information_schema.parameters
WHERE specific_schema = current_schema() AND specific_name = ' . q($C) . '
ORDER BY ordinal_position');
        return $I;
    }

    function
    routines()
    {
        return
            get_rows('SELECT specific_name AS "SPECIFIC_NAME", routine_type AS "ROUTINE_TYPE", routine_name AS "ROUTINE_NAME", type_udt_name AS "DTD_IDENTIFIER"
FROM information_schema.routines
WHERE routine_schema = current_schema()
ORDER BY SPECIFIC_NAME');
    }

    function
    routine_languages()
    {
        return
            get_vals("SELECT LOWER(lanname) FROM pg_catalog.pg_language");
    }

    function
    routine_id($C, $J)
    {
        $I = array();
        foreach ($J["fields"] as $p) $I[] = $p["type"];
        return
            idf_escape($C) . "(" . implode(", ", $I) . ")";
    }

    function
    last_id()
    {
        return
            0;
    }

    function
    explain($g, $G)
    {
        return $g->query("EXPLAIN $G");
    }

    function
    found_rows($R, $Z)
    {
        global $g;
        if (preg_match("~ rows=([0-9]+)~", $g->result("EXPLAIN SELECT * FROM " . idf_escape($R["Name"]) . ($Z ? " WHERE " . implode(" AND ", $Z) : "")), $Hg)) return $Hg[1];
        return
            false;
    }

    function
    types()
    {
        return
            get_vals("SELECT typname
FROM pg_type
WHERE typnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema())
AND typtype IN ('b','d','e')
AND typelem = 0");
    }

    function
    schemas()
    {
        return
            get_vals("SELECT nspname FROM pg_namespace ORDER BY nspname");
    }

    function
    get_schema()
    {
        global $g;
        return $g->result("SELECT current_schema()");
    }

    function
    set_schema($ah)
    {
        global $g, $U, $Ih;
        $I = $g->query("SET search_path TO " . idf_escape($ah));
        foreach (types() as $T) {
            if (!isset($U[$T])) {
                $U[$T] = 0;
                $Ih['User types'][] = $T;
            }
        }
        return $I;
    }

    function
    create_sql($Q, $Ma, $Jh)
    {
        global $g;
        $I = '';
        $Qg = array();
        $kh = array();
        $Fh = table_status($Q);
        $q = fields($Q);
        $x = indexes($Q);
        ksort($x);
        $Zc = foreign_keys($Q);
        ksort($Zc);
        if (!$Fh || empty($q)) return
            false;
        $I = "CREATE TABLE " . idf_escape($Fh['nspname']) . "." . idf_escape($Fh['Name']) . " (\n    ";
        foreach ($q
                 as $Rc => $p) {
            $Rf = idf_escape($p['field']) . ' ' . $p['full_type'] . default_value($p) . ($p['attnotnull'] ? " NOT NULL" : "");
            $Qg[] = $Rf;
            if (preg_match('~nextval\(\'([^\']+)\'\)~', $p['default'], $De)) {
                $jh = $De[1];
                $yh = reset(get_rows(min_version(10) ? "SELECT *, cache_size AS cache_value FROM pg_sequences WHERE schemaname = current_schema() AND sequencename = " . q($jh) : "SELECT * FROM $jh"));
                $kh[] = ($Jh == "DROP+CREATE" ? "DROP SEQUENCE IF EXISTS $jh;\n" : "") . "CREATE SEQUENCE $jh INCREMENT $yh[increment_by] MINVALUE $yh[min_value] MAXVALUE $yh[max_value] START " . ($Ma ? $yh['last_value'] : 1) . " CACHE $yh[cache_value];";
            }
        }
        if (!empty($kh)) $I = implode("\n\n", $kh) . "\n\n$I";
        foreach ($x
                 as $Id => $w) {
            switch ($w['type']) {
                case'UNIQUE':
                    $Qg[] = "CONSTRAINT " . idf_escape($Id) . " UNIQUE (" . implode(', ', array_map('idf_escape', $w['columns'])) . ")";
                    break;
                case'PRIMARY':
                    $Qg[] = "CONSTRAINT " . idf_escape($Id) . " PRIMARY KEY (" . implode(', ', array_map('idf_escape', $w['columns'])) . ")";
                    break;
            }
        }
        foreach ($Zc
                 as $Yc => $Xc) $Qg[] = "CONSTRAINT " . idf_escape($Yc) . " $Xc[definition] " . ($Xc['deferrable'] ? 'DEFERRABLE' : 'NOT DEFERRABLE');
        $I .= implode(",\n    ", $Qg) . "\n) WITH (oids = " . ($Fh['Oid'] ? 'true' : 'false') . ");";
        foreach ($x
                 as $Id => $w) {
            if ($w['type'] == 'INDEX') {
                $f = array();
                foreach ($w['columns'] as $z => $X) $f[] = idf_escape($X) . ($w['descs'][$z] ? " DESC" : "");
                $I .= "\n\nCREATE INDEX " . idf_escape($Id) . " ON " . idf_escape($Fh['nspname']) . "." . idf_escape($Fh['Name']) . " USING btree (" . implode(', ', $f) . ");";
            }
        }
        if ($Fh['Comment']) $I .= "\n\nCOMMENT ON TABLE " . idf_escape($Fh['nspname']) . "." . idf_escape($Fh['Name']) . " IS " . q($Fh['Comment']) . ";";
        foreach ($q
                 as $Rc => $p) {
            if ($p['comment']) $I .= "\n\nCOMMENT ON COLUMN " . idf_escape($Fh['nspname']) . "." . idf_escape($Fh['Name']) . "." . idf_escape($Rc) . " IS " . q($p['comment']) . ";";
        }
        return
            rtrim($I, ';');
    }

    function
    truncate_sql($Q)
    {
        return "TRUNCATE " . table($Q);
    }

    function
    trigger_sql($Q)
    {
        $Fh = table_status($Q);
        $I = "";
        foreach (triggers($Q) as $xi => $wi) {
            $yi = trigger($xi, $Fh['Name']);
            $I .= "\nCREATE TRIGGER " . idf_escape($yi['Trigger']) . " $yi[Timing] $yi[Events] ON " . idf_escape($Fh["nspname"]) . "." . idf_escape($Fh['Name']) . " $yi[Type] $yi[Statement];;\n";
        }
        return $I;
    }

    function
    use_sql($k)
    {
        return "\connect " . idf_escape($k);
    }

    function
    show_variables()
    {
        return
            get_key_vals("SHOW ALL");
    }

    function
    process_list()
    {
        return
            get_rows("SELECT * FROM pg_stat_activity ORDER BY " . (min_version(9.2) ? "pid" : "procpid"));
    }

    function
    show_status()
    {
    }

    function
    convert_field($p)
    {
    }

    function
    unconvert_field($p, $I)
    {
        return $I;
    }

    function
    support($Pc)
    {
        return
            preg_match('~^(database|table|columns|sql|indexes|descidx|comment|view|' . (min_version(9.3) ? 'materializedview|' : '') . 'scheme|routine|processlist|sequence|trigger|type|variables|drop_col|kill|dump)$~', $Pc);
    }

    function
    kill_process($X)
    {
        return
            queries("SELECT pg_terminate_backend(" . number($X) . ")");
    }

    function
    connection_id()
    {
        return "SELECT pg_backend_pid()";
    }

    function
    max_connections()
    {
        global $g;
        return $g->result("SHOW max_connections");
    }

    $y = "pgsql";
    $U = array();
    $Ih = array();
    foreach (array('Numbers' => array("smallint" => 5, "integer" => 10, "bigint" => 19, "boolean" => 1, "numeric" => 0, "real" => 7, "double precision" => 16, "money" => 20), 'Date and time' => array("date" => 13, "time" => 17, "timestamp" => 20, "timestamptz" => 21, "interval" => 0), 'Strings' => array("character" => 0, "character varying" => 0, "text" => 0, "tsquery" => 0, "tsvector" => 0, "uuid" => 0, "xml" => 0), 'Binary' => array("bit" => 0, "bit varying" => 0, "bytea" => 0), 'Network' => array("cidr" => 43, "inet" => 43, "macaddr" => 17, "txid_snapshot" => 0), 'Geometry' => array("box" => 0, "circle" => 0, "line" => 0, "lseg" => 0, "path" => 0, "point" => 0, "polygon" => 0),) as $z => $X) {
        $U += $X;
        $Ih[$z] = array_keys($X);
    }
    $Ji = array();
    $vf = array("=", "<", ">", "<=", ">=", "!=", "~", "!~", "LIKE", "LIKE %%", "ILIKE", "ILIKE %%", "IN", "IS NULL", "NOT LIKE", "NOT IN", "IS NOT NULL");
    $kd = array("char_length", "lower", "round", "to_hex", "to_timestamp", "upper");
    $qd = array("avg", "count", "count distinct", "max", "min", "sum");
    $mc = array(array("char" => "md5", "date|time" => "now",), array(number_type() => "+/-", "date|time" => "+ interval/- interval", "char|text" => "||",));
}
$ec["oracle"] = "Oracle (beta)";
if (isset($_GET["oracle"])) {
    $hg = array("OCI8", "PDO_OCI");
    define("DRIVER", "oracle");
    if (extension_loaded("oci8")) {
        class
        Min_DB
        {
            var $extension = "oci8", $_link, $_result, $server_info, $affected_rows, $errno, $error;

            function
            _error($xc, $o)
            {
                if (ini_bool("html_errors")) $o = html_entity_decode(strip_tags($o));
                $o = preg_replace('~^[^:]*: ~', '', $o);
                $this->error = $o;
            }

            function
            connect($N, $V, $F)
            {
                $this->_link = @oci_new_connect($V, $F, $N, "AL32UTF8");
                if ($this->_link) {
                    $this->server_info = oci_server_version($this->_link);
                    return
                        true;
                }
                $o = oci_error();
                $this->error = $o["message"];
                return
                    false;
            }

            function
            quote($P)
            {
                return "'" . str_replace("'", "''", $P) . "'";
            }

            function
            select_db($k)
            {
                return
                    true;
            }

            function
            query($G, $Di = false)
            {
                $H = oci_parse($this->_link, $G);
                $this->error = "";
                if (!$H) {
                    $o = oci_error($this->_link);
                    $this->errno = $o["code"];
                    $this->error = $o["message"];
                    return
                        false;
                }
                set_error_handler(array($this, '_error'));
                $I = @oci_execute($H);
                restore_error_handler();
                if ($I) {
                    if (oci_num_fields($H)) return
                        new
                        Min_Result($H);
                    $this->affected_rows = oci_num_rows($H);
                }
                return $I;
            }

            function
            multi_query($G)
            {
                return $this->_result = $this->query($G);
            }

            function
            store_result()
            {
                return $this->_result;
            }

            function
            next_result()
            {
                return
                    false;
            }

            function
            result($G, $p = 1)
            {
                $H = $this->query($G);
                if (!is_object($H) || !oci_fetch($H->_result)) return
                    false;
                return
                    oci_result($H->_result, $p);
            }
        }

        class
        Min_Result
        {
            var $_result, $_offset = 1, $num_rows;

            function
            __construct($H)
            {
                $this->_result = $H;
            }

            function
            _convert($J)
            {
                foreach ((array)$J
                         as $z => $X) {
                    if (is_a($X, 'OCI-Lob')) $J[$z] = $X->load();
                }
                return $J;
            }

            function
            fetch_assoc()
            {
                return $this->_convert(oci_fetch_assoc($this->_result));
            }

            function
            fetch_row()
            {
                return $this->_convert(oci_fetch_row($this->_result));
            }

            function
            fetch_field()
            {
                $e = $this->_offset++;
                $I = new
                stdClass;
                $I->name = oci_field_name($this->_result, $e);
                $I->orgname = $I->name;
                $I->type = oci_field_type($this->_result, $e);
                $I->charsetnr = (preg_match("~raw|blob|bfile~", $I->type) ? 63 : 0);
                return $I;
            }

            function
            __destruct()
            {
                oci_free_statement($this->_result);
            }
        }
    } elseif (extension_loaded("pdo_oci")) {
        class
        Min_DB
            extends
            Min_PDO
        {
            var $extension = "PDO_OCI";

            function
            connect($N, $V, $F)
            {
                $this->dsn("oci:dbname=//$N;charset=AL32UTF8", $V, $F);
                return
                    true;
            }

            function
            select_db($k)
            {
                return
                    true;
            }
        }
    }

    class
    Min_Driver
        extends
        Min_SQL
    {
        function
        begin()
        {
            return
                true;
        }
    }

    function
    idf_escape($v)
    {
        return '"' . str_replace('"', '""', $v) . '"';
    }

    function
    table($v)
    {
        return
            idf_escape($v);
    }

    function
    connect()
    {
        global $b;
        $g = new
        Min_DB;
        $j = $b->credentials();
        if ($g->connect($j[0], $j[1], $j[2])) return $g;
        return $g->error;
    }

    function
    get_databases()
    {
        return
            get_vals("SELECT tablespace_name FROM user_tablespaces");
    }

    function
    limit($G, $Z, $_, $D = 0, $M = " ")
    {
        return ($D ? " * FROM (SELECT t.*, rownum AS rnum FROM (SELECT $G$Z) t WHERE rownum <= " . ($_ + $D) . ") WHERE rnum > $D" : ($_ !== null ? " * FROM (SELECT $G$Z) WHERE rownum <= " . ($_ + $D) : " $G$Z"));
    }

    function
    limit1($Q, $G, $Z, $M = "\n")
    {
        return " $G$Z";
    }

    function
    db_collation($m, $pb)
    {
        global $g;
        return $g->result("SELECT value FROM nls_database_parameters WHERE parameter = 'NLS_CHARACTERSET'");
    }

    function
    engines()
    {
        return
            array();
    }

    function
    logged_user()
    {
        global $g;
        return $g->result("SELECT USER FROM DUAL");
    }

    function
    tables_list()
    {
        return
            get_key_vals("SELECT table_name, 'table' FROM all_tables WHERE tablespace_name = " . q(DB) . "
UNION SELECT view_name, 'view' FROM user_views
ORDER BY 1");
    }

    function
    count_tables($l)
    {
        return
            array();
    }

    function
    table_status($C = "")
    {
        $I = array();
        $ch = q($C);
        foreach (get_rows('SELECT table_name "Name", \'table\' "Engine", avg_row_len * num_rows "Data_length", num_rows "Rows" FROM all_tables WHERE tablespace_name = ' . q(DB) . ($C != "" ? " AND table_name = $ch" : "") . "
UNION SELECT view_name, 'view', 0, 0 FROM user_views" . ($C != "" ? " WHERE view_name = $ch" : "") . "
ORDER BY 1") as $J) {
            if ($C != "") return $J;
            $I[$J["Name"]] = $J;
        }
        return $I;
    }

    function
    is_view($R)
    {
        return $R["Engine"] == "view";
    }

    function
    fk_support($R)
    {
        return
            true;
    }

    function
    fields($Q)
    {
        $I = array();
        foreach (get_rows("SELECT * FROM all_tab_columns WHERE table_name = " . q($Q) . " ORDER BY column_id") as $J) {
            $T = $J["DATA_TYPE"];
            $te = "$J[DATA_PRECISION],$J[DATA_SCALE]";
            if ($te == ",") $te = $J["DATA_LENGTH"];
            $I[$J["COLUMN_NAME"]] = array("field" => $J["COLUMN_NAME"], "full_type" => $T . ($te ? "($te)" : ""), "type" => strtolower($T), "length" => $te, "default" => $J["DATA_DEFAULT"], "null" => ($J["NULLABLE"] == "Y"), "privileges" => array("insert" => 1, "select" => 1, "update" => 1),);
        }
        return $I;
    }

    function
    indexes($Q, $h = null)
    {
        $I = array();
        foreach (get_rows("SELECT uic.*, uc.constraint_type
FROM user_ind_columns uic
LEFT JOIN user_constraints uc ON uic.index_name = uc.constraint_name AND uic.table_name = uc.table_name
WHERE uic.table_name = " . q($Q) . "
ORDER BY uc.constraint_type, uic.column_position", $h) as $J) {
            $Id = $J["INDEX_NAME"];
            $I[$Id]["type"] = ($J["CONSTRAINT_TYPE"] == "P" ? "PRIMARY" : ($J["CONSTRAINT_TYPE"] == "U" ? "UNIQUE" : "INDEX"));
            $I[$Id]["columns"][] = $J["COLUMN_NAME"];
            $I[$Id]["lengths"][] = ($J["CHAR_LENGTH"] && $J["CHAR_LENGTH"] != $J["COLUMN_LENGTH"] ? $J["CHAR_LENGTH"] : null);
            $I[$Id]["descs"][] = ($J["DESCEND"] ? '1' : null);
        }
        return $I;
    }

    function
    view($C)
    {
        $K = get_rows('SELECT text "select" FROM user_views WHERE view_name = ' . q($C));
        return
            reset($K);
    }

    function
    collations()
    {
        return
            array();
    }

    function
    information_schema($m)
    {
        return
            false;
    }

    function
    error()
    {
        global $g;
        return
            h($g->error);
    }

    function
    explain($g, $G)
    {
        $g->query("EXPLAIN PLAN FOR $G");
        return $g->query("SELECT * FROM plan_table");
    }

    function
    found_rows($R, $Z)
    {
    }

    function
    alter_table($Q, $C, $q, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        $c = $fc = array();
        foreach ($q
                 as $p) {
            $X = $p[1];
            if ($X && $p[0] != "" && idf_escape($p[0]) != $X[0]) queries("ALTER TABLE " . table($Q) . " RENAME COLUMN " . idf_escape($p[0]) . " TO $X[0]");
            if ($X) $c[] = ($Q != "" ? ($p[0] != "" ? "MODIFY (" : "ADD (") : "  ") . implode($X) . ($Q != "" ? ")" : ""); else$fc[] = idf_escape($p[0]);
        }
        if ($Q == "") return
            queries("CREATE TABLE " . table($C) . " (\n" . implode(",\n", $c) . "\n)");
        return (!$c || queries("ALTER TABLE " . table($Q) . "\n" . implode("\n", $c))) && (!$fc || queries("ALTER TABLE " . table($Q) . " DROP (" . implode(", ", $fc) . ")")) && ($Q == $C || queries("ALTER TABLE " . table($Q) . " RENAME TO " . table($C)));
    }

    function
    foreign_keys($Q)
    {
        $I = array();
        $G = "SELECT c_list.CONSTRAINT_NAME as NAME,
c_src.COLUMN_NAME as SRC_COLUMN,
c_dest.OWNER as DEST_DB,
c_dest.TABLE_NAME as DEST_TABLE,
c_dest.COLUMN_NAME as DEST_COLUMN,
c_list.DELETE_RULE as ON_DELETE
FROM ALL_CONSTRAINTS c_list, ALL_CONS_COLUMNS c_src, ALL_CONS_COLUMNS c_dest
WHERE c_list.CONSTRAINT_NAME = c_src.CONSTRAINT_NAME
AND c_list.R_CONSTRAINT_NAME = c_dest.CONSTRAINT_NAME
AND c_list.CONSTRAINT_TYPE = 'R'
AND c_src.TABLE_NAME = " . q($Q);
        foreach (get_rows($G) as $J) $I[$J['NAME']] = array("db" => $J['DEST_DB'], "table" => $J['DEST_TABLE'], "source" => array($J['SRC_COLUMN']), "target" => array($J['DEST_COLUMN']), "on_delete" => $J['ON_DELETE'], "on_update" => null,);
        return $I;
    }

    function
    truncate_tables($S)
    {
        return
            apply_queries("TRUNCATE TABLE", $S);
    }

    function
    drop_views($bj)
    {
        return
            apply_queries("DROP VIEW", $bj);
    }

    function
    drop_tables($S)
    {
        return
            apply_queries("DROP TABLE", $S);
    }

    function
    last_id()
    {
        return
            0;
    }

    function
    schemas()
    {
        return
            get_vals("SELECT DISTINCT owner FROM dba_segments WHERE owner IN (SELECT username FROM dba_users WHERE default_tablespace NOT IN ('SYSTEM','SYSAUX'))");
    }

    function
    get_schema()
    {
        global $g;
        return $g->result("SELECT sys_context('USERENV', 'SESSION_USER') FROM dual");
    }

    function
    set_schema($bh)
    {
        global $g;
        return $g->query("ALTER SESSION SET CURRENT_SCHEMA = " . idf_escape($bh));
    }

    function
    show_variables()
    {
        return
            get_key_vals('SELECT name, display_value FROM v$parameter');
    }

    function
    process_list()
    {
        return
            get_rows('SELECT sess.process AS "process", sess.username AS "user", sess.schemaname AS "schema", sess.status AS "status", sess.wait_class AS "wait_class", sess.seconds_in_wait AS "seconds_in_wait", sql.sql_text AS "sql_text", sess.machine AS "machine", sess.port AS "port"
FROM v$session sess LEFT OUTER JOIN v$sql sql
ON sql.sql_id = sess.sql_id
WHERE sess.type = \'USER\'
ORDER BY PROCESS
');
    }

    function
    show_status()
    {
        $K = get_rows('SELECT * FROM v$instance');
        return
            reset($K);
    }

    function
    convert_field($p)
    {
    }

    function
    unconvert_field($p, $I)
    {
        return $I;
    }

    function
    support($Pc)
    {
        return
            preg_match('~^(columns|database|drop_col|indexes|descidx|processlist|scheme|sql|status|table|variables|view|view_trigger)$~', $Pc);
    }

    $y = "oracle";
    $U = array();
    $Ih = array();
    foreach (array('Numbers' => array("number" => 38, "binary_float" => 12, "binary_double" => 21), 'Date and time' => array("date" => 10, "timestamp" => 29, "interval year" => 12, "interval day" => 28), 'Strings' => array("char" => 2000, "varchar2" => 4000, "nchar" => 2000, "nvarchar2" => 4000, "clob" => 4294967295, "nclob" => 4294967295), 'Binary' => array("raw" => 2000, "long raw" => 2147483648, "blob" => 4294967295, "bfile" => 4294967296),) as $z => $X) {
        $U += $X;
        $Ih[$z] = array_keys($X);
    }
    $Ji = array();
    $vf = array("=", "<", ">", "<=", ">=", "!=", "LIKE", "LIKE %%", "IN", "IS NULL", "NOT LIKE", "NOT REGEXP", "NOT IN", "IS NOT NULL", "SQL");
    $kd = array("length", "lower", "round", "upper");
    $qd = array("avg", "count", "count distinct", "max", "min", "sum");
    $mc = array(array("date" => "current_date", "timestamp" => "current_timestamp",), array("number|float|double" => "+/-", "date|timestamp" => "+ interval/- interval", "char|clob" => "||",));
}
$ec["mssql"] = "MS SQL (beta)";
if (isset($_GET["mssql"])) {
    $hg = array("SQLSRV", "MSSQL", "PDO_DBLIB");
    define("DRIVER", "mssql");
    if (extension_loaded("sqlsrv")) {
        class
        Min_DB
        {
            var $extension = "sqlsrv", $_link, $_result, $server_info, $affected_rows, $errno, $error;

            function
            _get_error()
            {
                $this->error = "";
                foreach (sqlsrv_errors() as $o) {
                    $this->errno = $o["code"];
                    $this->error .= "$o[message]\n";
                }
                $this->error = rtrim($this->error);
            }

            function
            connect($N, $V, $F)
            {
                global $b;
                $m = $b->database();
                $yb = array("UID" => $V, "PWD" => $F, "CharacterSet" => "UTF-8");
                if ($m != "") $yb["Database"] = $m;
                $this->_link = @sqlsrv_connect(preg_replace('~:~', ',', $N), $yb);
                if ($this->_link) {
                    $Pd = sqlsrv_server_info($this->_link);
                    $this->server_info = $Pd['SQLServerVersion'];
                } else$this->_get_error();
                return (bool)$this->_link;
            }

            function
            quote($P)
            {
                return "'" . str_replace("'", "''", $P) . "'";
            }

            function
            select_db($k)
            {
                return $this->query("USE " . idf_escape($k));
            }

            function
            query($G, $Di = false)
            {
                $H = sqlsrv_query($this->_link, $G);
                $this->error = "";
                if (!$H) {
                    $this->_get_error();
                    return
                        false;
                }
                return $this->store_result($H);
            }

            function
            multi_query($G)
            {
                $this->_result = sqlsrv_query($this->_link, $G);
                $this->error = "";
                if (!$this->_result) {
                    $this->_get_error();
                    return
                        false;
                }
                return
                    true;
            }

            function
            store_result($H = null)
            {
                if (!$H) $H = $this->_result;
                if (!$H) return
                    false;
                if (sqlsrv_field_metadata($H)) return
                    new
                    Min_Result($H);
                $this->affected_rows = sqlsrv_rows_affected($H);
                return
                    true;
            }

            function
            next_result()
            {
                return $this->_result ? sqlsrv_next_result($this->_result) : null;
            }

            function
            result($G, $p = 0)
            {
                $H = $this->query($G);
                if (!is_object($H)) return
                    false;
                $J = $H->fetch_row();
                return $J[$p];
            }
        }

        class
        Min_Result
        {
            var $_result, $_offset = 0, $_fields, $num_rows;

            function
            __construct($H)
            {
                $this->_result = $H;
            }

            function
            _convert($J)
            {
                foreach ((array)$J
                         as $z => $X) {
                    if (is_a($X, 'DateTime')) $J[$z] = $X->format("Y-m-d H:i:s");
                }
                return $J;
            }

            function
            fetch_assoc()
            {
                return $this->_convert(sqlsrv_fetch_array($this->_result, SQLSRV_FETCH_ASSOC));
            }

            function
            fetch_row()
            {
                return $this->_convert(sqlsrv_fetch_array($this->_result, SQLSRV_FETCH_NUMERIC));
            }

            function
            fetch_field()
            {
                if (!$this->_fields) $this->_fields = sqlsrv_field_metadata($this->_result);
                $p = $this->_fields[$this->_offset++];
                $I = new
                stdClass;
                $I->name = $p["Name"];
                $I->orgname = $p["Name"];
                $I->type = ($p["Type"] == 1 ? 254 : 0);
                return $I;
            }

            function
            seek($D)
            {
                for ($t = 0; $t < $D; $t++) sqlsrv_fetch($this->_result);
            }

            function
            __destruct()
            {
                sqlsrv_free_stmt($this->_result);
            }
        }
    } elseif (extension_loaded("mssql")) {
        class
        Min_DB
        {
            var $extension = "MSSQL", $_link, $_result, $server_info, $affected_rows, $error;

            function
            connect($N, $V, $F)
            {
                $this->_link = @mssql_connect($N, $V, $F);
                if ($this->_link) {
                    $H = $this->query("SELECT SERVERPROPERTY('ProductLevel'), SERVERPROPERTY('Edition')");
                    if ($H) {
                        $J = $H->fetch_row();
                        $this->server_info = $this->result("sp_server_info 2", 2) . " [$J[0]] $J[1]";
                    }
                } else$this->error = mssql_get_last_message();
                return (bool)$this->_link;
            }

            function
            quote($P)
            {
                return "'" . str_replace("'", "''", $P) . "'";
            }

            function
            select_db($k)
            {
                return
                    mssql_select_db($k);
            }

            function
            query($G, $Di = false)
            {
                $H = @mssql_query($G, $this->_link);
                $this->error = "";
                if (!$H) {
                    $this->error = mssql_get_last_message();
                    return
                        false;
                }
                if ($H === true) {
                    $this->affected_rows = mssql_rows_affected($this->_link);
                    return
                        true;
                }
                return
                    new
                    Min_Result($H);
            }

            function
            multi_query($G)
            {
                return $this->_result = $this->query($G);
            }

            function
            store_result()
            {
                return $this->_result;
            }

            function
            next_result()
            {
                return
                    mssql_next_result($this->_result->_result);
            }

            function
            result($G, $p = 0)
            {
                $H = $this->query($G);
                if (!is_object($H)) return
                    false;
                return
                    mssql_result($H->_result, 0, $p);
            }
        }

        class
        Min_Result
        {
            var $_result, $_offset = 0, $_fields, $num_rows;

            function
            __construct($H)
            {
                $this->_result = $H;
                $this->num_rows = mssql_num_rows($H);
            }

            function
            fetch_assoc()
            {
                return
                    mssql_fetch_assoc($this->_result);
            }

            function
            fetch_row()
            {
                return
                    mssql_fetch_row($this->_result);
            }

            function
            num_rows()
            {
                return
                    mssql_num_rows($this->_result);
            }

            function
            fetch_field()
            {
                $I = mssql_fetch_field($this->_result);
                $I->orgtable = $I->table;
                $I->orgname = $I->name;
                return $I;
            }

            function
            seek($D)
            {
                mssql_data_seek($this->_result, $D);
            }

            function
            __destruct()
            {
                mssql_free_result($this->_result);
            }
        }
    } elseif (extension_loaded("pdo_dblib")) {
        class
        Min_DB
            extends
            Min_PDO
        {
            var $extension = "PDO_DBLIB";

            function
            connect($N, $V, $F)
            {
                $this->dsn("dblib:charset=utf8;host=" . str_replace(":", ";unix_socket=", preg_replace('~:(\d)~', ';port=\1', $N)), $V, $F);
                return
                    true;
            }

            function
            select_db($k)
            {
                return $this->query("USE " . idf_escape($k));
            }
        }
    }

    class
    Min_Driver
        extends
        Min_SQL
    {
        function
        insertUpdate($Q, $K, $kg)
        {
            foreach ($K
                     as $O) {
                $Ki = array();
                $Z = array();
                foreach ($O
                         as $z => $X) {
                    $Ki[] = "$z = $X";
                    if (isset($kg[idf_unescape($z)])) $Z[] = "$z = $X";
                }
                if (!queries("MERGE " . table($Q) . " USING (VALUES(" . implode(", ", $O) . ")) AS source (c" . implode(", c", range(1, count($O))) . ") ON " . implode(" AND ", $Z) . " WHEN MATCHED THEN UPDATE SET " . implode(", ", $Ki) . " WHEN NOT MATCHED THEN INSERT (" . implode(", ", array_keys($O)) . ") VALUES (" . implode(", ", $O) . ");")) return
                    false;
            }
            return
                true;
        }

        function
        begin()
        {
            return
                queries("BEGIN TRANSACTION");
        }
    }

    function
    idf_escape($v)
    {
        return "[" . str_replace("]", "]]", $v) . "]";
    }

    function
    table($v)
    {
        return ($_GET["ns"] != "" ? idf_escape($_GET["ns"]) . "." : "") . idf_escape($v);
    }

    function
    connect()
    {
        global $b;
        $g = new
        Min_DB;
        $j = $b->credentials();
        if ($g->connect($j[0], $j[1], $j[2])) return $g;
        return $g->error;
    }

    function
    get_databases()
    {
        return
            get_vals("SELECT name FROM sys.databases WHERE name NOT IN ('master', 'tempdb', 'model', 'msdb')");
    }

    function
    limit($G, $Z, $_, $D = 0, $M = " ")
    {
        return ($_ !== null ? " TOP (" . ($_ + $D) . ")" : "") . " $G$Z";
    }

    function
    limit1($Q, $G, $Z, $M = "\n")
    {
        return
            limit($G, $Z, 1, 0, $M);
    }

    function
    db_collation($m, $pb)
    {
        global $g;
        return $g->result("SELECT collation_name FROM sys.databases WHERE name = " . q($m));
    }

    function
    engines()
    {
        return
            array();
    }

    function
    logged_user()
    {
        global $g;
        return $g->result("SELECT SUSER_NAME()");
    }

    function
    tables_list()
    {
        return
            get_key_vals("SELECT name, type_desc FROM sys.all_objects WHERE schema_id = SCHEMA_ID(" . q(get_schema()) . ") AND type IN ('S', 'U', 'V') ORDER BY name");
    }

    function
    count_tables($l)
    {
        global $g;
        $I = array();
        foreach ($l
                 as $m) {
            $g->select_db($m);
            $I[$m] = $g->result("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES");
        }
        return $I;
    }

    function
    table_status($C = "")
    {
        $I = array();
        foreach (get_rows("SELECT ao.name AS Name, ao.type_desc AS Engine, (SELECT value FROM fn_listextendedproperty(default, 'SCHEMA', schema_name(schema_id), 'TABLE', ao.name, null, null)) AS Comment FROM sys.all_objects AS ao WHERE schema_id = SCHEMA_ID(" . q(get_schema()) . ") AND type IN ('S', 'U', 'V') " . ($C != "" ? "AND name = " . q($C) : "ORDER BY name")) as $J) {
            if ($C != "") return $J;
            $I[$J["Name"]] = $J;
        }
        return $I;
    }

    function
    is_view($R)
    {
        return $R["Engine"] == "VIEW";
    }

    function
    fk_support($R)
    {
        return
            true;
    }

    function
    fields($Q)
    {
        $vb = get_key_vals("SELECT objname, cast(value as varchar) FROM fn_listextendedproperty('MS_DESCRIPTION', 'schema', " . q(get_schema()) . ", 'table', " . q($Q) . ", 'column', NULL)");
        $I = array();
        foreach (get_rows("SELECT c.max_length, c.precision, c.scale, c.name, c.is_nullable, c.is_identity, c.collation_name, t.name type, CAST(d.definition as text) [default]
FROM sys.all_columns c
JOIN sys.all_objects o ON c.object_id = o.object_id
JOIN sys.types t ON c.user_type_id = t.user_type_id
LEFT JOIN sys.default_constraints d ON c.default_object_id = d.parent_column_id
WHERE o.schema_id = SCHEMA_ID(" . q(get_schema()) . ") AND o.type IN ('S', 'U', 'V') AND o.name = " . q($Q)) as $J) {
            $T = $J["type"];
            $te = (preg_match("~char|binary~", $T) ? $J["max_length"] : ($T == "decimal" ? "$J[precision],$J[scale]" : ""));
            $I[$J["name"]] = array("field" => $J["name"], "full_type" => $T . ($te ? "($te)" : ""), "type" => $T, "length" => $te, "default" => $J["default"], "null" => $J["is_nullable"], "auto_increment" => $J["is_identity"], "collation" => $J["collation_name"], "privileges" => array("insert" => 1, "select" => 1, "update" => 1), "primary" => $J["is_identity"], "comment" => $vb[$J["name"]],);
        }
        return $I;
    }

    function
    indexes($Q, $h = null)
    {
        $I = array();
        foreach (get_rows("SELECT i.name, key_ordinal, is_unique, is_primary_key, c.name AS column_name, is_descending_key
FROM sys.indexes i
INNER JOIN sys.index_columns ic ON i.object_id = ic.object_id AND i.index_id = ic.index_id
INNER JOIN sys.columns c ON ic.object_id = c.object_id AND ic.column_id = c.column_id
WHERE OBJECT_NAME(i.object_id) = " . q($Q), $h) as $J) {
            $C = $J["name"];
            $I[$C]["type"] = ($J["is_primary_key"] ? "PRIMARY" : ($J["is_unique"] ? "UNIQUE" : "INDEX"));
            $I[$C]["lengths"] = array();
            $I[$C]["columns"][$J["key_ordinal"]] = $J["column_name"];
            $I[$C]["descs"][$J["key_ordinal"]] = ($J["is_descending_key"] ? '1' : null);
        }
        return $I;
    }

    function
    view($C)
    {
        global $g;
        return
            array("select" => preg_replace('~^(?:[^[]|\[[^]]*])*\s+AS\s+~isU', '', $g->result("SELECT VIEW_DEFINITION FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_SCHEMA = SCHEMA_NAME() AND TABLE_NAME = " . q($C))));
    }

    function
    collations()
    {
        $I = array();
        foreach (get_vals("SELECT name FROM fn_helpcollations()") as $d) $I[preg_replace('~_.*~', '', $d)][] = $d;
        return $I;
    }

    function
    information_schema($m)
    {
        return
            false;
    }

    function
    error()
    {
        global $g;
        return
            nl_br(h(preg_replace('~^(\[[^]]*])+~m', '', $g->error)));
    }

    function
    create_database($m, $d)
    {
        return
            queries("CREATE DATABASE " . idf_escape($m) . (preg_match('~^[a-z0-9_]+$~i', $d) ? " COLLATE $d" : ""));
    }

    function
    drop_databases($l)
    {
        return
            queries("DROP DATABASE " . implode(", ", array_map('idf_escape', $l)));
    }

    function
    rename_database($C, $d)
    {
        if (preg_match('~^[a-z0-9_]+$~i', $d)) queries("ALTER DATABASE " . idf_escape(DB) . " COLLATE $d");
        queries("ALTER DATABASE " . idf_escape(DB) . " MODIFY NAME = " . idf_escape($C));
        return
            true;
    }

    function
    auto_increment()
    {
        return " IDENTITY" . ($_POST["Auto_increment"] != "" ? "(" . number($_POST["Auto_increment"]) . ",1)" : "") . " PRIMARY KEY";
    }

    function
    alter_table($Q, $C, $q, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        $c = array();
        $vb = array();
        foreach ($q
                 as $p) {
            $e = idf_escape($p[0]);
            $X = $p[1];
            if (!$X) $c["DROP"][] = " COLUMN $e"; else {
                $X[1] = preg_replace("~( COLLATE )'(\\w+)'~", '\1\2', $X[1]);
                $vb[$p[0]] = $X[5];
                unset($X[5]);
                if ($p[0] == "") $c["ADD"][] = "\n  " . implode("", $X) . ($Q == "" ? substr($cd[$X[0]], 16 + strlen($X[0])) : ""); else {
                    unset($X[6]);
                    if ($e != $X[0]) queries("EXEC sp_rename " . q(table($Q) . ".$e") . ", " . q(idf_unescape($X[0])) . ", 'COLUMN'");
                    $c["ALTER COLUMN " . implode("", $X)][] = "";
                }
            }
        }
        if ($Q == "") return
            queries("CREATE TABLE " . table($C) . " (" . implode(",", (array)$c["ADD"]) . "\n)");
        if ($Q != $C) queries("EXEC sp_rename " . q(table($Q)) . ", " . q($C));
        if ($cd) $c[""] = $cd;
        foreach ($c
                 as $z => $X) {
            if (!queries("ALTER TABLE " . idf_escape($C) . " $z" . implode(",", $X))) return
                false;
        }
        foreach ($vb
                 as $z => $X) {
            $ub = substr($X, 9);
            queries("EXEC sp_dropextendedproperty @name = N'MS_Description', @level0type = N'Schema', @level0name = " . q(get_schema()) . ", @level1type = N'Table',  @level1name = " . q($C) . ", @level2type = N'Column', @level2name = " . q($z));
            queries("EXEC sp_addextendedproperty @name = N'MS_Description', @value = " . $ub . ", @level0type = N'Schema', @level0name = " . q(get_schema()) . ", @level1type = N'Table',  @level1name = " . q($C) . ", @level2type = N'Column', @level2name = " . q($z));
        }
        return
            true;
    }

    function
    alter_indexes($Q, $c)
    {
        $w = array();
        $fc = array();
        foreach ($c
                 as $X) {
            if ($X[2] == "DROP") {
                if ($X[0] == "PRIMARY") $fc[] = idf_escape($X[1]); else$w[] = idf_escape($X[1]) . " ON " . table($Q);
            } elseif (!queries(($X[0] != "PRIMARY" ? "CREATE $X[0] " . ($X[0] != "INDEX" ? "INDEX " : "") . idf_escape($X[1] != "" ? $X[1] : uniqid($Q . "_")) . " ON " . table($Q) : "ALTER TABLE " . table($Q) . " ADD PRIMARY KEY") . " (" . implode(", ", $X[2]) . ")")) return
                false;
        }
        return (!$w || queries("DROP INDEX " . implode(", ", $w))) && (!$fc || queries("ALTER TABLE " . table($Q) . " DROP " . implode(", ", $fc)));
    }

    function
    last_id()
    {
        global $g;
        return $g->result("SELECT SCOPE_IDENTITY()");
    }

    function
    explain($g, $G)
    {
        $g->query("SET SHOWPLAN_ALL ON");
        $I = $g->query($G);
        $g->query("SET SHOWPLAN_ALL OFF");
        return $I;
    }

    function
    found_rows($R, $Z)
    {
    }

    function
    foreign_keys($Q)
    {
        $I = array();
        foreach (get_rows("EXEC sp_fkeys @fktable_name = " . q($Q)) as $J) {
            $r =& $I[$J["FK_NAME"]];
            $r["db"] = $J["PKTABLE_QUALIFIER"];
            $r["table"] = $J["PKTABLE_NAME"];
            $r["source"][] = $J["FKCOLUMN_NAME"];
            $r["target"][] = $J["PKCOLUMN_NAME"];
        }
        return $I;
    }

    function
    truncate_tables($S)
    {
        return
            apply_queries("TRUNCATE TABLE", $S);
    }

    function
    drop_views($bj)
    {
        return
            queries("DROP VIEW " . implode(", ", array_map('table', $bj)));
    }

    function
    drop_tables($S)
    {
        return
            queries("DROP TABLE " . implode(", ", array_map('table', $S)));
    }

    function
    move_tables($S, $bj, $Yh)
    {
        return
            apply_queries("ALTER SCHEMA " . idf_escape($Yh) . " TRANSFER", array_merge($S, $bj));
    }

    function
    trigger($C)
    {
        if ($C == "") return
            array();
        $K = get_rows("SELECT s.name [Trigger],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(s.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(s.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing],
c.text
FROM sysobjects s
JOIN syscomments c ON s.id = c.id
WHERE s.xtype = 'TR' AND s.name = " . q($C));
        $I = reset($K);
        if ($I) $I["Statement"] = preg_replace('~^.+\s+AS\s+~isU', '', $I["text"]);
        return $I;
    }

    function
    triggers($Q)
    {
        $I = array();
        foreach (get_rows("SELECT sys1.name,
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing]
FROM sysobjects sys1
JOIN sysobjects sys2 ON sys1.parent_obj = sys2.id
WHERE sys1.xtype = 'TR' AND sys2.name = " . q($Q)) as $J) $I[$J["name"]] = array($J["Timing"], $J["Event"]);
        return $I;
    }

    function
    trigger_options()
    {
        return
            array("Timing" => array("AFTER", "INSTEAD OF"), "Event" => array("INSERT", "UPDATE", "DELETE"), "Type" => array("AS"),);
    }

    function
    schemas()
    {
        return
            get_vals("SELECT name FROM sys.schemas");
    }

    function
    get_schema()
    {
        global $g;
        if ($_GET["ns"] != "") return $_GET["ns"];
        return $g->result("SELECT SCHEMA_NAME()");
    }

    function
    set_schema($ah)
    {
        return
            true;
    }

    function
    use_sql($k)
    {
        return "USE " . idf_escape($k);
    }

    function
    show_variables()
    {
        return
            array();
    }

    function
    show_status()
    {
        return
            array();
    }

    function
    convert_field($p)
    {
    }

    function
    unconvert_field($p, $I)
    {
        return $I;
    }

    function
    support($Pc)
    {
        return
            preg_match('~^(comment|columns|database|drop_col|indexes|descidx|scheme|sql|table|trigger|view|view_trigger)$~', $Pc);
    }

    $y = "mssql";
    $U = array();
    $Ih = array();
    foreach (array('Numbers' => array("tinyint" => 3, "smallint" => 5, "int" => 10, "bigint" => 20, "bit" => 1, "decimal" => 0, "real" => 12, "float" => 53, "smallmoney" => 10, "money" => 20), 'Date and time' => array("date" => 10, "smalldatetime" => 19, "datetime" => 19, "datetime2" => 19, "time" => 8, "datetimeoffset" => 10), 'Strings' => array("char" => 8000, "varchar" => 8000, "text" => 2147483647, "nchar" => 4000, "nvarchar" => 4000, "ntext" => 1073741823), 'Binary' => array("binary" => 8000, "varbinary" => 8000, "image" => 2147483647),) as $z => $X) {
        $U += $X;
        $Ih[$z] = array_keys($X);
    }
    $Ji = array();
    $vf = array("=", "<", ">", "<=", ">=", "!=", "LIKE", "LIKE %%", "IN", "IS NULL", "NOT LIKE", "NOT IN", "IS NOT NULL");
    $kd = array("len", "lower", "round", "upper");
    $qd = array("avg", "count", "count distinct", "max", "min", "sum");
    $mc = array(array("date|time" => "getdate",), array("int|decimal|real|float|money|datetime" => "+/-", "char|text" => "+",));
}
$ec['firebird'] = 'Firebird (alpha)';
if (isset($_GET["firebird"])) {
    $hg = array("interbase");
    define("DRIVER", "firebird");
    if (extension_loaded("interbase")) {
        class
        Min_DB
        {
            var $extension = "Firebird", $server_info, $affected_rows, $errno, $error, $_link, $_result;

            function
            connect($N, $V, $F)
            {
                $this->_link = ibase_connect($N, $V, $F);
                if ($this->_link) {
                    $Ni = explode(':', $N);
                    $this->service_link = ibase_service_attach($Ni[0], $V, $F);
                    $this->server_info = ibase_server_info($this->service_link, IBASE_SVC_SERVER_VERSION);
                } else {
                    $this->errno = ibase_errcode();
                    $this->error = ibase_errmsg();
                }
                return (bool)$this->_link;
            }

            function
            quote($P)
            {
                return "'" . str_replace("'", "''", $P) . "'";
            }

            function
            select_db($k)
            {
                return ($k == "domain");
            }

            function
            query($G, $Di = false)
            {
                $H = ibase_query($G, $this->_link);
                if (!$H) {
                    $this->errno = ibase_errcode();
                    $this->error = ibase_errmsg();
                    return
                        false;
                }
                $this->error = "";
                if ($H === true) {
                    $this->affected_rows = ibase_affected_rows($this->_link);
                    return
                        true;
                }
                return
                    new
                    Min_Result($H);
            }

            function
            multi_query($G)
            {
                return $this->_result = $this->query($G);
            }

            function
            store_result()
            {
                return $this->_result;
            }

            function
            next_result()
            {
                return
                    false;
            }

            function
            result($G, $p = 0)
            {
                $H = $this->query($G);
                if (!$H || !$H->num_rows) return
                    false;
                $J = $H->fetch_row();
                return $J[$p];
            }
        }

        class
        Min_Result
        {
            var $num_rows, $_result, $_offset = 0;

            function
            __construct($H)
            {
                $this->_result = $H;
            }

            function
            fetch_assoc()
            {
                return
                    ibase_fetch_assoc($this->_result);
            }

            function
            fetch_row()
            {
                return
                    ibase_fetch_row($this->_result);
            }

            function
            fetch_field()
            {
                $p = ibase_field_info($this->_result, $this->_offset++);
                return (object)array('name' => $p['name'], 'orgname' => $p['name'], 'type' => $p['type'], 'charsetnr' => $p['length'],);
            }

            function
            __destruct()
            {
                ibase_free_result($this->_result);
            }
        }
    }

    class
    Min_Driver
        extends
        Min_SQL
    {
    }

    function
    idf_escape($v)
    {
        return '"' . str_replace('"', '""', $v) . '"';
    }

    function
    table($v)
    {
        return
            idf_escape($v);
    }

    function
    connect()
    {
        global $b;
        $g = new
        Min_DB;
        $j = $b->credentials();
        if ($g->connect($j[0], $j[1], $j[2])) return $g;
        return $g->error;
    }

    function
    get_databases($ad)
    {
        return
            array("domain");
    }

    function
    limit($G, $Z, $_, $D = 0, $M = " ")
    {
        $I = '';
        $I .= ($_ !== null ? $M . "FIRST $_" . ($D ? " SKIP $D" : "") : "");
        $I .= " $G$Z";
        return $I;
    }

    function
    limit1($Q, $G, $Z, $M = "\n")
    {
        return
            limit($G, $Z, 1, 0, $M);
    }

    function
    db_collation($m, $pb)
    {
    }

    function
    engines()
    {
        return
            array();
    }

    function
    logged_user()
    {
        global $b;
        $j = $b->credentials();
        return $j[1];
    }

    function
    tables_list()
    {
        global $g;
        $G = 'SELECT RDB$RELATION_NAME FROM rdb$relations WHERE rdb$system_flag = 0';
        $H = ibase_query($g->_link, $G);
        $I = array();
        while ($J = ibase_fetch_assoc($H)) $I[$J['RDB$RELATION_NAME']] = 'table';
        ksort($I);
        return $I;
    }

    function
    count_tables($l)
    {
        return
            array();
    }

    function
    table_status($C = "", $Oc = false)
    {
        global $g;
        $I = array();
        $Lb = tables_list();
        foreach ($Lb
                 as $w => $X) {
            $w = trim($w);
            $I[$w] = array('Name' => $w, 'Engine' => 'standard',);
            if ($C == $w) return $I[$w];
        }
        return $I;
    }

    function
    is_view($R)
    {
        return
            false;
    }

    function
    fk_support($R)
    {
        return
            preg_match('~InnoDB|IBMDB2I~i', $R["Engine"]);
    }

    function
    fields($Q)
    {
        global $g;
        $I = array();
        $G = 'SELECT r.RDB$FIELD_NAME AS field_name,
r.RDB$DESCRIPTION AS field_description,
r.RDB$DEFAULT_VALUE AS field_default_value,
r.RDB$NULL_FLAG AS field_not_null_constraint,
f.RDB$FIELD_LENGTH AS field_length,
f.RDB$FIELD_PRECISION AS field_precision,
f.RDB$FIELD_SCALE AS field_scale,
CASE f.RDB$FIELD_TYPE
WHEN 261 THEN \'BLOB\'
WHEN 14 THEN \'CHAR\'
WHEN 40 THEN \'CSTRING\'
WHEN 11 THEN \'D_FLOAT\'
WHEN 27 THEN \'DOUBLE\'
WHEN 10 THEN \'FLOAT\'
WHEN 16 THEN \'INT64\'
WHEN 8 THEN \'INTEGER\'
WHEN 9 THEN \'QUAD\'
WHEN 7 THEN \'SMALLINT\'
WHEN 12 THEN \'DATE\'
WHEN 13 THEN \'TIME\'
WHEN 35 THEN \'TIMESTAMP\'
WHEN 37 THEN \'VARCHAR\'
ELSE \'UNKNOWN\'
END AS field_type,
f.RDB$FIELD_SUB_TYPE AS field_subtype,
coll.RDB$COLLATION_NAME AS field_collation,
cset.RDB$CHARACTER_SET_NAME AS field_charset
FROM RDB$RELATION_FIELDS r
LEFT JOIN RDB$FIELDS f ON r.RDB$FIELD_SOURCE = f.RDB$FIELD_NAME
LEFT JOIN RDB$COLLATIONS coll ON f.RDB$COLLATION_ID = coll.RDB$COLLATION_ID
LEFT JOIN RDB$CHARACTER_SETS cset ON f.RDB$CHARACTER_SET_ID = cset.RDB$CHARACTER_SET_ID
WHERE r.RDB$RELATION_NAME = ' . q($Q) . '
ORDER BY r.RDB$FIELD_POSITION';
        $H = ibase_query($g->_link, $G);
        while ($J = ibase_fetch_assoc($H)) $I[trim($J['FIELD_NAME'])] = array("field" => trim($J["FIELD_NAME"]), "full_type" => trim($J["FIELD_TYPE"]), "type" => trim($J["FIELD_SUB_TYPE"]), "default" => trim($J['FIELD_DEFAULT_VALUE']), "null" => (trim($J["FIELD_NOT_NULL_CONSTRAINT"]) == "YES"), "auto_increment" => '0', "collation" => trim($J["FIELD_COLLATION"]), "privileges" => array("insert" => 1, "select" => 1, "update" => 1), "comment" => trim($J["FIELD_DESCRIPTION"]),);
        return $I;
    }

    function
    indexes($Q, $h = null)
    {
        $I = array();
        return $I;
    }

    function
    foreign_keys($Q)
    {
        return
            array();
    }

    function
    collations()
    {
        return
            array();
    }

    function
    information_schema($m)
    {
        return
            false;
    }

    function
    error()
    {
        global $g;
        return
            h($g->error);
    }

    function
    types()
    {
        return
            array();
    }

    function
    schemas()
    {
        return
            array();
    }

    function
    get_schema()
    {
        return "";
    }

    function
    set_schema($ah)
    {
        return
            true;
    }

    function
    support($Pc)
    {
        return
            preg_match("~^(columns|sql|status|table)$~", $Pc);
    }

    $y = "firebird";
    $vf = array("=");
    $kd = array();
    $qd = array();
    $mc = array();
}
$ec["simpledb"] = "SimpleDB";
if (isset($_GET["simpledb"])) {
    $hg = array("SimpleXML + allow_url_fopen");
    define("DRIVER", "simpledb");
    if (class_exists('SimpleXMLElement') && ini_bool('allow_url_fopen')) {
        class
        Min_DB
        {
            var $extension = "SimpleXML", $server_info = '2009-04-15', $error, $timeout, $next, $affected_rows, $_result;

            function
            select_db($k)
            {
                return ($k == "domain");
            }

            function
            query($G, $Di = false)
            {
                $Of = array('SelectExpression' => $G, 'ConsistentRead' => 'true');
                if ($this->next) $Of['NextToken'] = $this->next;
                $H = sdb_request_all('Select', 'Item', $Of, $this->timeout);
                $this->timeout = 0;
                if ($H === false) return $H;
                if (preg_match('~^\s*SELECT\s+COUNT\(~i', $G)) {
                    $Mh = 0;
                    foreach ($H
                             as $be) $Mh += $be->Attribute->Value;
                    $H = array((object)array('Attribute' => array((object)array('Name' => 'Count', 'Value' => $Mh,))));
                }
                return
                    new
                    Min_Result($H);
            }

            function
            multi_query($G)
            {
                return $this->_result = $this->query($G);
            }

            function
            store_result()
            {
                return $this->_result;
            }

            function
            next_result()
            {
                return
                    false;
            }

            function
            quote($P)
            {
                return "'" . str_replace("'", "''", $P) . "'";
            }
        }

        class
        Min_Result
        {
            var $num_rows, $_rows = array(), $_offset = 0;

            function
            __construct($H)
            {
                foreach ($H
                         as $be) {
                    $J = array();
                    if ($be->Name != '') $J['itemName()'] = (string)$be->Name;
                    foreach ($be->Attribute
                             as $Ia) {
                        $C = $this->_processValue($Ia->Name);
                        $Y = $this->_processValue($Ia->Value);
                        if (isset($J[$C])) {
                            $J[$C] = (array)$J[$C];
                            $J[$C][] = $Y;
                        } else$J[$C] = $Y;
                    }
                    $this->_rows[] = $J;
                    foreach ($J
                             as $z => $X) {
                        if (!isset($this->_rows[0][$z])) $this->_rows[0][$z] = null;
                    }
                }
                $this->num_rows = count($this->_rows);
            }

            function
            _processValue($pc)
            {
                return (is_object($pc) && $pc['encoding'] == 'base64' ? base64_decode($pc) : (string)$pc);
            }

            function
            fetch_assoc()
            {
                $J = current($this->_rows);
                if (!$J) return $J;
                $I = array();
                foreach ($this->_rows[0] as $z => $X) $I[$z] = $J[$z];
                next($this->_rows);
                return $I;
            }

            function
            fetch_row()
            {
                $I = $this->fetch_assoc();
                if (!$I) return $I;
                return
                    array_values($I);
            }

            function
            fetch_field()
            {
                $he = array_keys($this->_rows[0]);
                return (object)array('name' => $he[$this->_offset++]);
            }
        }
    }

    class
    Min_Driver
        extends
        Min_SQL
    {
        public $kg = "itemName()";

        function
        _chunkRequest($Ed, $va, $Of, $Ec = array())
        {
            global $g;
            foreach (array_chunk($Ed, 25) as $ib) {
                $Pf = $Of;
                foreach ($ib
                         as $t => $u) {
                    $Pf["Item.$t.ItemName"] = $u;
                    foreach ($Ec
                             as $z => $X) $Pf["Item.$t.$z"] = $X;
                }
                if (!sdb_request($va, $Pf)) return
                    false;
            }
            $g->affected_rows = count($Ed);
            return
                true;
        }

        function
        _extractIds($Q, $wg, $_)
        {
            $I = array();
            if (preg_match_all("~itemName\(\) = (('[^']*+')+)~", $wg, $De)) $I = array_map('idf_unescape', $De[1]); else {
                foreach (sdb_request_all('Select', 'Item', array('SelectExpression' => 'SELECT itemName() FROM ' . table($Q) . $wg . ($_ ? " LIMIT 1" : ""))) as $be) $I[] = $be->Name;
            }
            return $I;
        }

        function
        select($Q, $L, $Z, $nd, $_f = array(), $_ = 1, $E = 0, $mg = false)
        {
            global $g;
            $g->next = $_GET["next"];
            $I = parent::select($Q, $L, $Z, $nd, $_f, $_, $E, $mg);
            $g->next = 0;
            return $I;
        }

        function
        delete($Q, $wg, $_ = 0)
        {
            return $this->_chunkRequest($this->_extractIds($Q, $wg, $_), 'BatchDeleteAttributes', array('DomainName' => $Q));
        }

        function
        update($Q, $O, $wg, $_ = 0, $M = "\n")
        {
            $Ub = array();
            $Td = array();
            $t = 0;
            $Ed = $this->_extractIds($Q, $wg, $_);
            $u = idf_unescape($O["`itemName()`"]);
            unset($O["`itemName()`"]);
            foreach ($O
                     as $z => $X) {
                $z = idf_unescape($z);
                if ($X == "NULL" || ($u != "" && array($u) != $Ed)) $Ub["Attribute." . count($Ub) . ".Name"] = $z;
                if ($X != "NULL") {
                    foreach ((array)$X
                             as $de => $W) {
                        $Td["Attribute.$t.Name"] = $z;
                        $Td["Attribute.$t.Value"] = (is_array($X) ? $W : idf_unescape($W));
                        if (!$de) $Td["Attribute.$t.Replace"] = "true";
                        $t++;
                    }
                }
            }
            $Of = array('DomainName' => $Q);
            return (!$Td || $this->_chunkRequest(($u != "" ? array($u) : $Ed), 'BatchPutAttributes', $Of, $Td)) && (!$Ub || $this->_chunkRequest($Ed, 'BatchDeleteAttributes', $Of, $Ub));
        }

        function
        insert($Q, $O)
        {
            $Of = array("DomainName" => $Q);
            $t = 0;
            foreach ($O
                     as $C => $Y) {
                if ($Y != "NULL") {
                    $C = idf_unescape($C);
                    if ($C == "itemName()") $Of["ItemName"] = idf_unescape($Y); else {
                        foreach ((array)$Y
                                 as $X) {
                            $Of["Attribute.$t.Name"] = $C;
                            $Of["Attribute.$t.Value"] = (is_array($Y) ? $X : idf_unescape($Y));
                            $t++;
                        }
                    }
                }
            }
            return
                sdb_request('PutAttributes', $Of);
        }

        function
        insertUpdate($Q, $K, $kg)
        {
            foreach ($K
                     as $O) {
                if (!$this->update($Q, $O, "WHERE `itemName()` = " . q($O["`itemName()`"]))) return
                    false;
            }
            return
                true;
        }

        function
        begin()
        {
            return
                false;
        }

        function
        commit()
        {
            return
                false;
        }

        function
        rollback()
        {
            return
                false;
        }

        function
        slowQuery($G, $gi)
        {
            $this->_conn->timeout = $gi;
            return $G;
        }
    }

    function
    connect()
    {
        global $b;
        list(, , $F) = $b->credentials();
        if ($F != "") return 'Database does not support password.';
        return
            new
            Min_DB;
    }

    function
    support($Pc)
    {
        return
            preg_match('~sql~', $Pc);
    }

    function
    logged_user()
    {
        global $b;
        $j = $b->credentials();
        return $j[1];
    }

    function
    get_databases()
    {
        return
            array("domain");
    }

    function
    collations()
    {
        return
            array();
    }

    function
    db_collation($m, $pb)
    {
    }

    function
    tables_list()
    {
        global $g;
        $I = array();
        foreach (sdb_request_all('ListDomains', 'DomainName') as $Q) $I[(string)$Q] = 'table';
        if ($g->error && defined("PAGE_HEADER")) echo "<p class='error'>" . error() . "\n";
        return $I;
    }

    function
    table_status($C = "", $Oc = false)
    {
        $I = array();
        foreach (($C != "" ? array($C => true) : tables_list()) as $Q => $T) {
            $J = array("Name" => $Q, "Auto_increment" => "");
            if (!$Oc) {
                $Qe = sdb_request('DomainMetadata', array('DomainName' => $Q));
                if ($Qe) {
                    foreach (array("Rows" => "ItemCount", "Data_length" => "ItemNamesSizeBytes", "Index_length" => "AttributeValuesSizeBytes", "Data_free" => "AttributeNamesSizeBytes",) as $z => $X) $J[$z] = (string)$Qe->$X;
                }
            }
            if ($C != "") return $J;
            $I[$Q] = $J;
        }
        return $I;
    }

    function
    explain($g, $G)
    {
    }

    function
    error()
    {
        global $g;
        return
            h($g->error);
    }

    function
    information_schema()
    {
    }

    function
    is_view($R)
    {
    }

    function
    indexes($Q, $h = null)
    {
        return
            array(array("type" => "PRIMARY", "columns" => array("itemName()")),);
    }

    function
    fields($Q)
    {
        return
            fields_from_edit();
    }

    function
    foreign_keys($Q)
    {
        return
            array();
    }

    function
    table($v)
    {
        return
            idf_escape($v);
    }

    function
    idf_escape($v)
    {
        return "`" . str_replace("`", "``", $v) . "`";
    }

    function
    limit($G, $Z, $_, $D = 0, $M = " ")
    {
        return " $G$Z" . ($_ !== null ? $M . "LIMIT $_" : "");
    }

    function
    unconvert_field($p, $I)
    {
        return $I;
    }

    function
    fk_support($R)
    {
    }

    function
    engines()
    {
        return
            array();
    }

    function
    alter_table($Q, $C, $q, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        return ($Q == "" && sdb_request('CreateDomain', array('DomainName' => $C)));
    }

    function
    drop_tables($S)
    {
        foreach ($S
                 as $Q) {
            if (!sdb_request('DeleteDomain', array('DomainName' => $Q))) return
                false;
        }
        return
            true;
    }

    function
    count_tables($l)
    {
        foreach ($l
                 as $m) return
            array($m => count(tables_list()));
    }

    function
    found_rows($R, $Z)
    {
        return ($Z ? null : $R["Rows"]);
    }

    function
    last_id()
    {
    }

    function
    hmac($Ba, $Lb, $z, $_g = false)
    {
        $Va = 64;
        if (strlen($z) > $Va) $z = pack("H*", $Ba($z));
        $z = str_pad($z, $Va, "\0");
        $ee = $z ^ str_repeat("\x36", $Va);
        $fe = $z ^ str_repeat("\x5C", $Va);
        $I = $Ba($fe . pack("H*", $Ba($ee . $Lb)));
        if ($_g) $I = pack("H*", $I);
        return $I;
    }

    function
    sdb_request($va, $Of = array())
    {
        global $b, $g;
        list($Ad, $Of['AWSAccessKeyId'], $dh) = $b->credentials();
        $Of['Action'] = $va;
        $Of['Timestamp'] = gmdate('Y-m-d\TH:i:s+00:00');
        $Of['Version'] = '2009-04-15';
        $Of['SignatureVersion'] = 2;
        $Of['SignatureMethod'] = 'HmacSHA1';
        ksort($Of);
        $G = '';
        foreach ($Of
                 as $z => $X) $G .= '&' . rawurlencode($z) . '=' . rawurlencode($X);
        $G = str_replace('%7E', '~', substr($G, 1));
        $G .= "&Signature=" . urlencode(base64_encode(hmac('sha1', "POST\n" . preg_replace('~^https?://~', '', $Ad) . "\n/\n$G", $dh, true)));
        @ini_set('track_errors', 1);
        $Tc = @file_get_contents((preg_match('~^https?://~', $Ad) ? $Ad : "http://$Ad"), false, stream_context_create(array('http' => array('method' => 'POST', 'content' => $G, 'ignore_errors' => 1,))));
        if (!$Tc) {
            $g->error = $php_errormsg;
            return
                false;
        }
        libxml_use_internal_errors(true);
        $oj = simplexml_load_string($Tc);
        if (!$oj) {
            $o = libxml_get_last_error();
            $g->error = $o->message;
            return
                false;
        }
        if ($oj->Errors) {
            $o = $oj->Errors->Error;
            $g->error = "$o->Message ($o->Code)";
            return
                false;
        }
        $g->error = '';
        $Xh = $va . "Result";
        return ($oj->$Xh ? $oj->$Xh : true);
    }

    function
    sdb_request_all($va, $Xh, $Of = array(), $gi = 0)
    {
        $I = array();
        $Dh = ($gi ? microtime(true) : 0);
        $_ = (preg_match('~LIMIT\s+(\d+)\s*$~i', $Of['SelectExpression'], $B) ? $B[1] : 0);
        do {
            $oj = sdb_request($va, $Of);
            if (!$oj) break;
            foreach ($oj->$Xh
                     as $pc) $I[] = $pc;
            if ($_ && count($I) >= $_) {
                $_GET["next"] = $oj->NextToken;
                break;
            }
            if ($gi && microtime(true) - $Dh > $gi) return
                false;
            $Of['NextToken'] = $oj->NextToken;
            if ($_) $Of['SelectExpression'] = preg_replace('~\d+\s*$~', $_ - count($I), $Of['SelectExpression']);
        } while ($oj->NextToken);
        return $I;
    }

    $y = "simpledb";
    $vf = array("=", "<", ">", "<=", ">=", "!=", "LIKE", "LIKE %%", "IN", "IS NULL", "NOT LIKE", "IS NOT NULL");
    $kd = array();
    $qd = array("count");
    $mc = array(array("json"));
}
$ec["mongo"] = "MongoDB";
if (isset($_GET["mongo"])) {
    $hg = array("mongo", "mongodb");
    define("DRIVER", "mongo");
    if (class_exists('MongoDB')) {
        class
        Min_DB
        {
            var $extension = "Mongo", $server_info = MongoClient::VERSION, $error, $last_id, $_link, $_db;

            function
            connect($Li, $yf)
            {
                return @new
                MongoClient($Li, $yf);
            }

            function
            query($G)
            {
                return
                    false;
            }

            function
            select_db($k)
            {
                try {
                    $this->_db = $this->_link->selectDB($k);
                    return
                        true;
                } catch (Exception$Ac) {
                    $this->error = $Ac->getMessage();
                    return
                        false;
                }
            }

            function
            quote($P)
            {
                return $P;
            }
        }

        class
        Min_Result
        {
            var $num_rows, $_rows = array(), $_offset = 0, $_charset = array();

            function
            __construct($H)
            {
                foreach ($H
                         as $be) {
                    $J = array();
                    foreach ($be
                             as $z => $X) {
                        if (is_a($X, 'MongoBinData')) $this->_charset[$z] = 63;
                        $J[$z] = (is_a($X, 'MongoId') ? 'ObjectId("' . strval($X) . '")' : (is_a($X, 'MongoDate') ? gmdate("Y-m-d H:i:s", $X->sec) . " GMT" : (is_a($X, 'MongoBinData') ? $X->bin : (is_a($X, 'MongoRegex') ? strval($X) : (is_object($X) ? get_class($X) : $X)))));
                    }
                    $this->_rows[] = $J;
                    foreach ($J
                             as $z => $X) {
                        if (!isset($this->_rows[0][$z])) $this->_rows[0][$z] = null;
                    }
                }
                $this->num_rows = count($this->_rows);
            }

            function
            fetch_assoc()
            {
                $J = current($this->_rows);
                if (!$J) return $J;
                $I = array();
                foreach ($this->_rows[0] as $z => $X) $I[$z] = $J[$z];
                next($this->_rows);
                return $I;
            }

            function
            fetch_row()
            {
                $I = $this->fetch_assoc();
                if (!$I) return $I;
                return
                    array_values($I);
            }

            function
            fetch_field()
            {
                $he = array_keys($this->_rows[0]);
                $C = $he[$this->_offset++];
                return (object)array('name' => $C, 'charsetnr' => $this->_charset[$C],);
            }
        }

        class
        Min_Driver
            extends
            Min_SQL
        {
            public $kg = "_id";

            function
            select($Q, $L, $Z, $nd, $_f = array(), $_ = 1, $E = 0, $mg = false)
            {
                $L = ($L == array("*") ? array() : array_fill_keys($L, true));
                $vh = array();
                foreach ($_f
                         as $X) {
                    $X = preg_replace('~ DESC$~', '', $X, 1, $Eb);
                    $vh[$X] = ($Eb ? -1 : 1);
                }
                return
                    new
                    Min_Result($this->_conn->_db->selectCollection($Q)->find(array(), $L)->sort($vh)->limit($_ != "" ? +$_ : 0)->skip($E * $_));
            }

            function
            insert($Q, $O)
            {
                try {
                    $I = $this->_conn->_db->selectCollection($Q)->insert($O);
                    $this->_conn->errno = $I['code'];
                    $this->_conn->error = $I['err'];
                    $this->_conn->last_id = $O['_id'];
                    return !$I['err'];
                } catch (Exception$Ac) {
                    $this->_conn->error = $Ac->getMessage();
                    return
                        false;
                }
            }
        }

        function
        get_databases($ad)
        {
            global $g;
            $I = array();
            $Qb = $g->_link->listDBs();
            foreach ($Qb['databases'] as $m) $I[] = $m['name'];
            return $I;
        }

        function
        count_tables($l)
        {
            global $g;
            $I = array();
            foreach ($l
                     as $m) $I[$m] = count($g->_link->selectDB($m)->getCollectionNames(true));
            return $I;
        }

        function
        tables_list()
        {
            global $g;
            return
                array_fill_keys($g->_db->getCollectionNames(true), 'table');
        }

        function
        drop_databases($l)
        {
            global $g;
            foreach ($l
                     as $m) {
                $Mg = $g->_link->selectDB($m)->drop();
                if (!$Mg['ok']) return
                    false;
            }
            return
                true;
        }

        function
        indexes($Q, $h = null)
        {
            global $g;
            $I = array();
            foreach ($g->_db->selectCollection($Q)->getIndexInfo() as $w) {
                $Xb = array();
                foreach ($w["key"] as $e => $T) $Xb[] = ($T == -1 ? '1' : null);
                $I[$w["name"]] = array("type" => ($w["name"] == "_id_" ? "PRIMARY" : ($w["unique"] ? "UNIQUE" : "INDEX")), "columns" => array_keys($w["key"]), "lengths" => array(), "descs" => $Xb,);
            }
            return $I;
        }

        function
        fields($Q)
        {
            return
                fields_from_edit();
        }

        function
        found_rows($R, $Z)
        {
            global $g;
            return $g->_db->selectCollection($_GET["select"])->count($Z);
        }

        $vf = array("=");
    } elseif (class_exists('MongoDB\Driver\Manager')) {
        class
        Min_DB
        {
            var $extension = "MongoDB", $server_info = MONGODB_VERSION, $error, $last_id;
            var $_link;
            var $_db, $_db_name;

            function
            connect($Li, $yf)
            {
                $kb = 'MongoDB\Driver\Manager';
                return
                    new$kb($Li, $yf);
            }

            function
            query($G)
            {
                return
                    false;
            }

            function
            select_db($k)
            {
                $this->_db_name = $k;
                return
                    true;
            }

            function
            quote($P)
            {
                return $P;
            }
        }

        class
        Min_Result
        {
            var $num_rows, $_rows = array(), $_offset = 0, $_charset = array();

            function
            __construct($H)
            {
                foreach ($H
                         as $be) {
                    $J = array();
                    foreach ($be
                             as $z => $X) {
                        if (is_a($X, 'MongoDB\BSON\Binary')) $this->_charset[$z] = 63;
                        $J[$z] = (is_a($X, 'MongoDB\BSON\ObjectID') ? 'MongoDB\BSON\ObjectID("' . strval($X) . '")' : (is_a($X, 'MongoDB\BSON\UTCDatetime') ? $X->toDateTime()->format('Y-m-d H:i:s') : (is_a($X, 'MongoDB\BSON\Binary') ? $X->bin : (is_a($X, 'MongoDB\BSON\Regex') ? strval($X) : (is_object($X) ? json_encode($X, 256) : $X)))));
                    }
                    $this->_rows[] = $J;
                    foreach ($J
                             as $z => $X) {
                        if (!isset($this->_rows[0][$z])) $this->_rows[0][$z] = null;
                    }
                }
                $this->num_rows = $H->count;
            }

            function
            fetch_assoc()
            {
                $J = current($this->_rows);
                if (!$J) return $J;
                $I = array();
                foreach ($this->_rows[0] as $z => $X) $I[$z] = $J[$z];
                next($this->_rows);
                return $I;
            }

            function
            fetch_row()
            {
                $I = $this->fetch_assoc();
                if (!$I) return $I;
                return
                    array_values($I);
            }

            function
            fetch_field()
            {
                $he = array_keys($this->_rows[0]);
                $C = $he[$this->_offset++];
                return (object)array('name' => $C, 'charsetnr' => $this->_charset[$C],);
            }
        }

        class
        Min_Driver
            extends
            Min_SQL
        {
            public $kg = "_id";

            function
            select($Q, $L, $Z, $nd, $_f = array(), $_ = 1, $E = 0, $mg = false)
            {
                global $g;
                $L = ($L == array("*") ? array() : array_fill_keys($L, 1));
                if (count($L) && !isset($L['_id'])) $L['_id'] = 0;
                $Z = where_to_query($Z);
                $vh = array();
                foreach ($_f
                         as $X) {
                    $X = preg_replace('~ DESC$~', '', $X, 1, $Eb);
                    $vh[$X] = ($Eb ? -1 : 1);
                }
                if (isset($_GET['limit']) && is_numeric($_GET['limit']) && $_GET['limit'] > 0) $_ = $_GET['limit'];
                $_ = min(200, max(1, (int)$_));
                $sh = $E * $_;
                $kb = 'MongoDB\Driver\Query';
                $G = new$kb($Z, array('projection' => $L, 'limit' => $_, 'skip' => $sh, 'sort' => $vh));
                $Pg = $g->_link->executeQuery("$g->_db_name.$Q", $G);
                return
                    new
                    Min_Result($Pg);
            }

            function
            update($Q, $O, $wg, $_ = 0, $M = "\n")
            {
                global $g;
                $m = $g->_db_name;
                $Z = sql_query_where_parser($wg);
                $kb = 'MongoDB\Driver\BulkWrite';
                $Za = new$kb(array());
                if (isset($O['_id'])) unset($O['_id']);
                $Jg = array();
                foreach ($O
                         as $z => $Y) {
                    if ($Y == 'NULL') {
                        $Jg[$z] = 1;
                        unset($O[$z]);
                    }
                }
                $Ki = array('$set' => $O);
                if (count($Jg)) $Ki['$unset'] = $Jg;
                $Za->update($Z, $Ki, array('upsert' => false));
                $Pg = $g->_link->executeBulkWrite("$m.$Q", $Za);
                $g->affected_rows = $Pg->getModifiedCount();
                return
                    true;
            }

            function
            delete($Q, $wg, $_ = 0)
            {
                global $g;
                $m = $g->_db_name;
                $Z = sql_query_where_parser($wg);
                $kb = 'MongoDB\Driver\BulkWrite';
                $Za = new$kb(array());
                $Za->delete($Z, array('limit' => $_));
                $Pg = $g->_link->executeBulkWrite("$m.$Q", $Za);
                $g->affected_rows = $Pg->getDeletedCount();
                return
                    true;
            }

            function
            insert($Q, $O)
            {
                global $g;
                $m = $g->_db_name;
                $kb = 'MongoDB\Driver\BulkWrite';
                $Za = new$kb(array());
                if (isset($O['_id']) && empty($O['_id'])) unset($O['_id']);
                $Za->insert($O);
                $Pg = $g->_link->executeBulkWrite("$m.$Q", $Za);
                $g->affected_rows = $Pg->getInsertedCount();
                return
                    true;
            }
        }

        function
        get_databases($ad)
        {
            global $g;
            $I = array();
            $kb = 'MongoDB\Driver\Command';
            $sb = new$kb(array('listDatabases' => 1));
            $Pg = $g->_link->executeCommand('admin', $sb);
            foreach ($Pg
                     as $Qb) {
                foreach ($Qb->databases
                         as $m) $I[] = $m->name;
            }
            return $I;
        }

        function
        count_tables($l)
        {
            $I = array();
            return $I;
        }

        function
        tables_list()
        {
            global $g;
            $kb = 'MongoDB\Driver\Command';
            $sb = new$kb(array('listCollections' => 1));
            $Pg = $g->_link->executeCommand($g->_db_name, $sb);
            $qb = array();
            foreach ($Pg
                     as $H) $qb[$H->name] = 'table';
            return $qb;
        }

        function
        drop_databases($l)
        {
            return
                false;
        }

        function
        indexes($Q, $h = null)
        {
            global $g;
            $I = array();
            $kb = 'MongoDB\Driver\Command';
            $sb = new$kb(array('listIndexes' => $Q));
            $Pg = $g->_link->executeCommand($g->_db_name, $sb);
            foreach ($Pg
                     as $w) {
                $Xb = array();
                $f = array();
                foreach (get_object_vars($w->key) as $e => $T) {
                    $Xb[] = ($T == -1 ? '1' : null);
                    $f[] = $e;
                }
                $I[$w->name] = array("type" => ($w->name == "_id_" ? "PRIMARY" : (isset($w->unique) ? "UNIQUE" : "INDEX")), "columns" => $f, "lengths" => array(), "descs" => $Xb,);
            }
            return $I;
        }

        function
        fields($Q)
        {
            $q = fields_from_edit();
            if (!count($q)) {
                global $n;
                $H = $n->select($Q, array("*"), null, null, array(), 10);
                while ($J = $H->fetch_assoc()) {
                    foreach ($J
                             as $z => $X) {
                        $J[$z] = null;
                        $q[$z] = array("field" => $z, "type" => "string", "null" => ($z != $n->primary), "auto_increment" => ($z == $n->primary), "privileges" => array("insert" => 1, "select" => 1, "update" => 1,),);
                    }
                }
            }
            return $q;
        }

        function
        found_rows($R, $Z)
        {
            global $g;
            $Z = where_to_query($Z);
            $kb = 'MongoDB\Driver\Command';
            $sb = new$kb(array('count' => $R['Name'], 'query' => $Z));
            $Pg = $g->_link->executeCommand($g->_db_name, $sb);
            $oi = $Pg->toArray();
            return $oi[0]->n;
        }

        function
        sql_query_where_parser($wg)
        {
            $wg = trim(preg_replace('/WHERE[\s]?[(]?\(?/', '', $wg));
            $wg = preg_replace('/\)\)\)$/', ')', $wg);
            $lj = explode(' AND ', $wg);
            $mj = explode(') OR (', $wg);
            $Z = array();
            foreach ($lj
                     as $jj) $Z[] = trim($jj);
            if (count($mj) == 1) $mj = array(); elseif (count($mj) > 1) $Z = array();
            return
                where_to_query($Z, $mj);
        }

        function
        where_to_query($hj = array(), $ij = array())
        {
            global $b;
            $Lb = array();
            foreach (array('and' => $hj, 'or' => $ij) as $T => $Z) {
                if (is_array($Z)) {
                    foreach ($Z
                             as $Hc) {
                        list($nb, $tf, $X) = explode(" ", $Hc, 3);
                        if ($nb == "_id") {
                            $X = str_replace('MongoDB\BSON\ObjectID("', "", $X);
                            $X = str_replace('")', "", $X);
                            $kb = 'MongoDB\BSON\ObjectID';
                            $X = new$kb($X);
                        }
                        if (!in_array($tf, $b->operators)) continue;
                        if (preg_match('~^\(f\)(.+)~', $tf, $B)) {
                            $X = (float)$X;
                            $tf = $B[1];
                        } elseif (preg_match('~^\(date\)(.+)~', $tf, $B)) {
                            $Nb = new
                            DateTime($X);
                            $kb = 'MongoDB\BSON\UTCDatetime';
                            $X = new$kb($Nb->getTimestamp() * 1000);
                            $tf = $B[1];
                        }
                        switch ($tf) {
                            case'=':
                                $tf = '$eq';
                                break;
                            case'!=':
                                $tf = '$ne';
                                break;
                            case'>':
                                $tf = '$gt';
                                break;
                            case'<':
                                $tf = '$lt';
                                break;
                            case'>=':
                                $tf = '$gte';
                                break;
                            case'<=':
                                $tf = '$lte';
                                break;
                            case'regex':
                                $tf = '$regex';
                                break;
                            default:
                                continue
                                2;
                        }
                        if ($T == 'and') $Lb['$and'][] = array($nb => array($tf => $X)); elseif ($T == 'or') $Lb['$or'][] = array($nb => array($tf => $X));
                    }
                }
            }
            return $Lb;
        }

        $vf = array("=", "!=", ">", "<", ">=", "<=", "regex", "(f)=", "(f)!=", "(f)>", "(f)<", "(f)>=", "(f)<=", "(date)=", "(date)!=", "(date)>", "(date)<", "(date)>=", "(date)<=",);
    }
    function
    table($v)
    {
        return $v;
    }

    function
    idf_escape($v)
    {
        return $v;
    }

    function
    table_status($C = "", $Oc = false)
    {
        $I = array();
        foreach (tables_list() as $Q => $T) {
            $I[$Q] = array("Name" => $Q);
            if ($C == $Q) return $I[$Q];
        }
        return $I;
    }

    function
    create_database($m, $d)
    {
        return
            true;
    }

    function
    last_id()
    {
        global $g;
        return $g->last_id;
    }

    function
    error()
    {
        global $g;
        return
            h($g->error);
    }

    function
    collations()
    {
        return
            array();
    }

    function
    logged_user()
    {
        global $b;
        $j = $b->credentials();
        return $j[1];
    }

    function
    connect()
    {
        global $b;
        $g = new
        Min_DB;
        list($N, $V, $F) = $b->credentials();
        $yf = array();
        if ($V . $F != "") {
            $yf["username"] = $V;
            $yf["password"] = $F;
        }
        $m = $b->database();
        if ($m != "") $yf["db"] = $m;
        if (($La = getenv("MONGO_AUTH_SOURCE"))) $yf["authSource"] = $La;
        try {
            $g->_link = $g->connect("mongodb://$N", $yf);
            if ($F != "") {
                $yf["password"] = "";
                try {
                    $g->connect("mongodb://$N", $yf);
                    return 'Database does not support password.';
                } catch (Exception$Ac) {
                }
            }
            return $g;
        } catch (Exception$Ac) {
            return $Ac->getMessage();
        }
    }

    function
    alter_indexes($Q, $c)
    {
        global $g;
        foreach ($c
                 as $X) {
            list($T, $C, $O) = $X;
            if ($O == "DROP") $I = $g->_db->command(array("deleteIndexes" => $Q, "index" => $C)); else {
                $f = array();
                foreach ($O
                         as $e) {
                    $e = preg_replace('~ DESC$~', '', $e, 1, $Eb);
                    $f[$e] = ($Eb ? -1 : 1);
                }
                $I = $g->_db->selectCollection($Q)->ensureIndex($f, array("unique" => ($T == "UNIQUE"), "name" => $C,));
            }
            if ($I['errmsg']) {
                $g->error = $I['errmsg'];
                return
                    false;
            }
        }
        return
            true;
    }

    function
    support($Pc)
    {
        return
            preg_match("~database|indexes|descidx~", $Pc);
    }

    function
    db_collation($m, $pb)
    {
    }

    function
    information_schema()
    {
    }

    function
    is_view($R)
    {
    }

    function
    convert_field($p)
    {
    }

    function
    unconvert_field($p, $I)
    {
        return $I;
    }

    function
    foreign_keys($Q)
    {
        return
            array();
    }

    function
    fk_support($R)
    {
    }

    function
    engines()
    {
        return
            array();
    }

    function
    alter_table($Q, $C, $q, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        global $g;
        if ($Q == "") {
            $g->_db->createCollection($C);
            return
                true;
        }
    }

    function
    drop_tables($S)
    {
        global $g;
        foreach ($S
                 as $Q) {
            $Mg = $g->_db->selectCollection($Q)->drop();
            if (!$Mg['ok']) return
                false;
        }
        return
            true;
    }

    function
    truncate_tables($S)
    {
        global $g;
        foreach ($S
                 as $Q) {
            $Mg = $g->_db->selectCollection($Q)->remove();
            if (!$Mg['ok']) return
                false;
        }
        return
            true;
    }

    $y = "mongo";
    $kd = array();
    $qd = array();
    $mc = array(array("json"));
}
$ec["elastic"] = "Elasticsearch (beta)";
if (isset($_GET["elastic"])) {
    $hg = array("json + allow_url_fopen");
    define("DRIVER", "elastic");
    if (function_exists('json_decode') && ini_bool('allow_url_fopen')) {
        class
        Min_DB
        {
            var $extension = "JSON", $server_info, $errno, $error, $_url;

            function
            rootQuery($Yf, $_b = array(), $Re = 'GET')
            {
                @ini_set('track_errors', 1);
                $Tc = @file_get_contents("$this->_url/" . ltrim($Yf, '/'), false, stream_context_create(array('http' => array('method' => $Re, 'content' => $_b === null ? $_b : json_encode($_b), 'header' => 'Content-Type: application/json', 'ignore_errors' => 1,))));
                if (!$Tc) {
                    $this->error = $php_errormsg;
                    return $Tc;
                }
                if (!preg_match('~^HTTP/[0-9.]+ 2~i', $http_response_header[0])) {
                    $this->error = $Tc;
                    return
                        false;
                }
                $I = json_decode($Tc, true);
                if ($I === null) {
                    $this->errno = json_last_error();
                    if (function_exists('json_last_error_msg')) $this->error = json_last_error_msg(); else {
                        $zb = get_defined_constants(true);
                        foreach ($zb['json'] as $C => $Y) {
                            if ($Y == $this->errno && preg_match('~^JSON_ERROR_~', $C)) {
                                $this->error = $C;
                                break;
                            }
                        }
                    }
                }
                return $I;
            }

            function
            query($Yf, $_b = array(), $Re = 'GET')
            {
                return $this->rootQuery(($this->_db != "" ? "$this->_db/" : "/") . ltrim($Yf, '/'), $_b, $Re);
            }

            function
            connect($N, $V, $F)
            {
                preg_match('~^(https?://)?(.*)~', $N, $B);
                $this->_url = ($B[1] ? $B[1] : "http://") . "$V:$F@$B[2]";
                $I = $this->query('');
                if ($I) $this->server_info = $I['version']['number'];
                return (bool)$I;
            }

            function
            select_db($k)
            {
                $this->_db = $k;
                return
                    true;
            }

            function
            quote($P)
            {
                return $P;
            }
        }

        class
        Min_Result
        {
            var $num_rows, $_rows;

            function
            __construct($K)
            {
                $this->num_rows = count($K);
                $this->_rows = $K;
                reset($this->_rows);
            }

            function
            fetch_assoc()
            {
                $I = current($this->_rows);
                next($this->_rows);
                return $I;
            }

            function
            fetch_row()
            {
                return
                    array_values($this->fetch_assoc());
            }
        }
    }

    class
    Min_Driver
        extends
        Min_SQL
    {
        function
        select($Q, $L, $Z, $nd, $_f = array(), $_ = 1, $E = 0, $mg = false)
        {
            global $b;
            $Lb = array();
            $G = "$Q/_search";
            if ($L != array("*")) $Lb["fields"] = $L;
            if ($_f) {
                $vh = array();
                foreach ($_f
                         as $nb) {
                    $nb = preg_replace('~ DESC$~', '', $nb, 1, $Eb);
                    $vh[] = ($Eb ? array($nb => "desc") : $nb);
                }
                $Lb["sort"] = $vh;
            }
            if ($_) {
                $Lb["size"] = +$_;
                if ($E) $Lb["from"] = ($E * $_);
            }
            foreach ($Z
                     as $X) {
                list($nb, $tf, $X) = explode(" ", $X, 3);
                if ($nb == "_id") $Lb["query"]["ids"]["values"][] = $X; elseif ($nb . $X != "") {
                    $bi = array("term" => array(($nb != "" ? $nb : "_all") => $X));
                    if ($tf == "=") $Lb["query"]["filtered"]["filter"]["and"][] = $bi; else$Lb["query"]["filtered"]["query"]["bool"]["must"][] = $bi;
                }
            }
            if ($Lb["query"] && !$Lb["query"]["filtered"]["query"] && !$Lb["query"]["ids"]) $Lb["query"]["filtered"]["query"] = array("match_all" => array());
            $Dh = microtime(true);
            $ch = $this->_conn->query($G, $Lb);
            if ($mg) echo $b->selectQuery("$G: " . json_encode($Lb), $Dh, !$ch);
            if (!$ch) return
                false;
            $I = array();
            foreach ($ch['hits']['hits'] as $_d) {
                $J = array();
                if ($L == array("*")) $J["_id"] = $_d["_id"];
                $q = $_d['_source'];
                if ($L != array("*")) {
                    $q = array();
                    foreach ($L
                             as $z) $q[$z] = $_d['fields'][$z];
                }
                foreach ($q
                         as $z => $X) {
                    if ($Lb["fields"]) $X = $X[0];
                    $J[$z] = (is_array($X) ? json_encode($X) : $X);
                }
                $I[] = $J;
            }
            return
                new
                Min_Result($I);
        }

        function
        update($T, $Ag, $wg, $_ = 0, $M = "\n")
        {
            $Wf = preg_split('~ *= *~', $wg);
            if (count($Wf) == 2) {
                $u = trim($Wf[1]);
                $G = "$T/$u";
                return $this->_conn->query($G, $Ag, 'POST');
            }
            return
                false;
        }

        function
        insert($T, $Ag)
        {
            $u = "";
            $G = "$T/$u";
            $Mg = $this->_conn->query($G, $Ag, 'POST');
            $this->_conn->last_id = $Mg['_id'];
            return $Mg['created'];
        }

        function
        delete($T, $wg, $_ = 0)
        {
            $Ed = array();
            if (is_array($_GET["where"]) && $_GET["where"]["_id"]) $Ed[] = $_GET["where"]["_id"];
            if (is_array($_POST['check'])) {
                foreach ($_POST['check'] as $db) {
                    $Wf = preg_split('~ *= *~', $db);
                    if (count($Wf) == 2) $Ed[] = trim($Wf[1]);
                }
            }
            $this->_conn->affected_rows = 0;
            foreach ($Ed
                     as $u) {
                $G = "{$T}/{$u}";
                $Mg = $this->_conn->query($G, '{}', 'DELETE');
                if (is_array($Mg) && $Mg['found'] == true) $this->_conn->affected_rows++;
            }
            return $this->_conn->affected_rows;
        }
    }

    function
    connect()
    {
        global $b;
        $g = new
        Min_DB;
        list($N, $V, $F) = $b->credentials();
        if ($F != "" && $g->connect($N, $V, "")) return 'Database does not support password.';
        if ($g->connect($N, $V, $F)) return $g;
        return $g->error;
    }

    function
    support($Pc)
    {
        return
            preg_match("~database|table|columns~", $Pc);
    }

    function
    logged_user()
    {
        global $b;
        $j = $b->credentials();
        return $j[1];
    }

    function
    get_databases()
    {
        global $g;
        $I = $g->rootQuery('_aliases');
        if ($I) {
            $I = array_keys($I);
            sort($I, SORT_STRING);
        }
        return $I;
    }

    function
    collations()
    {
        return
            array();
    }

    function
    db_collation($m, $pb)
    {
    }

    function
    engines()
    {
        return
            array();
    }

    function
    count_tables($l)
    {
        global $g;
        $I = array();
        $H = $g->query('_stats');
        if ($H && $H['indices']) {
            $Md = $H['indices'];
            foreach ($Md
                     as $Ld => $Eh) {
                $Kd = $Eh['total']['indexing'];
                $I[$Ld] = $Kd['index_total'];
            }
        }
        return $I;
    }

    function
    tables_list()
    {
        global $g;
        $I = $g->query('_mapping');
        if ($I) $I = array_fill_keys(array_keys($I[$g->_db]["mappings"]), 'table');
        return $I;
    }

    function
    table_status($C = "", $Oc = false)
    {
        global $g;
        $ch = $g->query("_search", array("size" => 0, "aggregations" => array("count_by_type" => array("terms" => array("field" => "_type")))), "POST");
        $I = array();
        if ($ch) {
            $S = $ch["aggregations"]["count_by_type"]["buckets"];
            foreach ($S
                     as $Q) {
                $I[$Q["key"]] = array("Name" => $Q["key"], "Engine" => "table", "Rows" => $Q["doc_count"],);
                if ($C != "" && $C == $Q["key"]) return $I[$C];
            }
        }
        return $I;
    }

    function
    error()
    {
        global $g;
        return
            h($g->error);
    }

    function
    information_schema()
    {
    }

    function
    is_view($R)
    {
    }

    function
    indexes($Q, $h = null)
    {
        return
            array(array("type" => "PRIMARY", "columns" => array("_id")),);
    }

    function
    fields($Q)
    {
        global $g;
        $H = $g->query("$Q/_mapping");
        $I = array();
        if ($H) {
            $_e = $H[$Q]['properties'];
            if (!$_e) $_e = $H[$g->_db]['mappings'][$Q]['properties'];
            if ($_e) {
                foreach ($_e
                         as $C => $p) {
                    $I[$C] = array("field" => $C, "full_type" => $p["type"], "type" => $p["type"], "privileges" => array("insert" => 1, "select" => 1, "update" => 1),);
                    if ($p["properties"]) {
                        unset($I[$C]["privileges"]["insert"]);
                        unset($I[$C]["privileges"]["update"]);
                    }
                }
            }
        }
        return $I;
    }

    function
    foreign_keys($Q)
    {
        return
            array();
    }

    function
    table($v)
    {
        return $v;
    }

    function
    idf_escape($v)
    {
        return $v;
    }

    function
    convert_field($p)
    {
    }

    function
    unconvert_field($p, $I)
    {
        return $I;
    }

    function
    fk_support($R)
    {
    }

    function
    found_rows($R, $Z)
    {
        return
            null;
    }

    function
    create_database($m)
    {
        global $g;
        return $g->rootQuery(urlencode($m), null, 'PUT');
    }

    function
    drop_databases($l)
    {
        global $g;
        return $g->rootQuery(urlencode(implode(',', $l)), array(), 'DELETE');
    }

    function
    alter_table($Q, $C, $q, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        global $g;
        $sg = array();
        foreach ($q
                 as $Mc) {
            $Rc = trim($Mc[1][0]);
            $Sc = trim($Mc[1][1] ? $Mc[1][1] : "text");
            $sg[$Rc] = array('type' => $Sc);
        }
        if (!empty($sg)) $sg = array('properties' => $sg);
        return $g->query("_mapping/{$C}", $sg, 'PUT');
    }

    function
    drop_tables($S)
    {
        global $g;
        $I = true;
        foreach ($S
                 as $Q) $I = $I && $g->query(urlencode($Q), array(), 'DELETE');
        return $I;
    }

    function
    last_id()
    {
        global $g;
        return $g->last_id;
    }

    $y = "elastic";
    $vf = array("=", "query");
    $kd = array();
    $qd = array();
    $mc = array(array("json"));
    $U = array();
    $Ih = array();
    foreach (array('Numbers' => array("long" => 3, "integer" => 5, "short" => 8, "byte" => 10, "double" => 20, "float" => 66, "half_float" => 12, "scaled_float" => 21), 'Date and time' => array("date" => 10), 'Strings' => array("string" => 65535, "text" => 65535), 'Binary' => array("binary" => 255),) as $z => $X) {
        $U += $X;
        $Ih[$z] = array_keys($X);
    }
}
$ec["clickhouse"] = "ClickHouse (alpha)";
if (isset($_GET["clickhouse"])) {
    define("DRIVER", "clickhouse");

    class
    Min_DB
    {
        var $extension = "JSON", $server_info, $errno, $_result, $error, $_url;
        var $_db = 'default';

        function
        rootQuery($m, $G)
        {
            @ini_set('track_errors', 1);
            $Tc = @file_get_contents("$this->_url/?database=$m", false, stream_context_create(array('http' => array('method' => 'POST', 'content' => $this->isQuerySelectLike($G) ? "$G FORMAT JSONCompact" : $G, 'header' => 'Content-type: application/x-www-form-urlencoded', 'ignore_errors' => 1,))));
            if ($Tc === false) {
                $this->error = $php_errormsg;
                return $Tc;
            }
            if (!preg_match('~^HTTP/[0-9.]+ 2~i', $http_response_header[0])) {
                $this->error = $Tc;
                return
                    false;
            }
            $I = json_decode($Tc, true);
            if ($I === null) {
                $this->errno = json_last_error();
                if (function_exists('json_last_error_msg')) $this->error = json_last_error_msg(); else {
                    $zb = get_defined_constants(true);
                    foreach ($zb['json'] as $C => $Y) {
                        if ($Y == $this->errno && preg_match('~^JSON_ERROR_~', $C)) {
                            $this->error = $C;
                            break;
                        }
                    }
                }
            }
            return
                new
                Min_Result($I);
        }

        function
        isQuerySelectLike($G)
        {
            return (bool)preg_match('~^(select|show)~i', $G);
        }

        function
        query($G)
        {
            return $this->rootQuery($this->_db, $G);
        }

        function
        connect($N, $V, $F)
        {
            preg_match('~^(https?://)?(.*)~', $N, $B);
            $this->_url = ($B[1] ? $B[1] : "http://") . "$V:$F@$B[2]";
            $I = $this->query('SELECT 1');
            return (bool)$I;
        }

        function
        select_db($k)
        {
            $this->_db = $k;
            return
                true;
        }

        function
        quote($P)
        {
            return "'" . addcslashes($P, "\\'") . "'";
        }

        function
        multi_query($G)
        {
            return $this->_result = $this->query($G);
        }

        function
        store_result()
        {
            return $this->_result;
        }

        function
        next_result()
        {
            return
                false;
        }

        function
        result($G, $p = 0)
        {
            $H = $this->query($G);
            return $H['data'];
        }
    }

    class
    Min_Result
    {
        var $num_rows, $_rows, $columns, $meta, $_offset = 0;

        function
        __construct($H)
        {
            $this->num_rows = $H['rows'];
            $this->_rows = $H['data'];
            $this->meta = $H['meta'];
            $this->columns = array_column($this->meta, 'name');
            reset($this->_rows);
        }

        function
        fetch_assoc()
        {
            $J = current($this->_rows);
            next($this->_rows);
            return $J === false ? false : array_combine($this->columns, $J);
        }

        function
        fetch_row()
        {
            $J = current($this->_rows);
            next($this->_rows);
            return $J;
        }

        function
        fetch_field()
        {
            $e = $this->_offset++;
            $I = new
            stdClass;
            if ($e < count($this->columns)) {
                $I->name = $this->meta[$e]['name'];
                $I->orgname = $I->name;
                $I->type = $this->meta[$e]['type'];
            }
            return $I;
        }
    }

    class
    Min_Driver
        extends
        Min_SQL
    {
        function
        delete($Q, $wg, $_ = 0)
        {
            return
                queries("ALTER TABLE " . table($Q) . " DELETE $wg");
        }

        function
        update($Q, $O, $wg, $_ = 0, $M = "\n")
        {
            $Wi = array();
            foreach ($O
                     as $z => $X) $Wi[] = "$z = $X";
            $G = $M . implode(",$M", $Wi);
            return
                queries("ALTER TABLE " . table($Q) . " UPDATE $G$wg");
        }
    }

    function
    idf_escape($v)
    {
        return "`" . str_replace("`", "``", $v) . "`";
    }

    function
    table($v)
    {
        return
            idf_escape($v);
    }

    function
    explain($g, $G)
    {
        return '';
    }

    function
    found_rows($R, $Z)
    {
        $K = get_vals("SELECT COUNT(*) FROM " . idf_escape($R["Name"]) . ($Z ? " WHERE " . implode(" AND ", $Z) : ""));
        return
            empty($K) ? false : $K[0];
    }

    function
    alter_table($Q, $C, $q, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        foreach ($q
                 as $p) {
            if ($p[1][2] === " NULL") $p[1][1] = " Nullable({$p[1][1]})";
            unset($p[1][2]);
        }
    }

    function
    truncate_tables($S)
    {
        return
            apply_queries("TRUNCATE TABLE", $S);
    }

    function
    drop_views($bj)
    {
        return
            drop_tables($bj);
    }

    function
    drop_tables($S)
    {
        return
            apply_queries("DROP TABLE", $S);
    }

    function
    connect()
    {
        global $b;
        $g = new
        Min_DB;
        $j = $b->credentials();
        if ($g->connect($j[0], $j[1], $j[2])) return $g;
        return $g->error;
    }

    function
    get_databases($ad)
    {
        global $g;
        $H = get_rows('SHOW DATABASES');
        $I = array();
        foreach ($H
                 as $J) $I[] = $J['name'];
        sort($I);
        return $I;
    }

    function
    limit($G, $Z, $_, $D = 0, $M = " ")
    {
        return " $G$Z" . ($_ !== null ? $M . "LIMIT $_" . ($D ? ", $D" : "") : "");
    }

    function
    limit1($Q, $G, $Z, $M = "\n")
    {
        return
            limit($G, $Z, 1, 0, $M);
    }

    function
    db_collation($m, $pb)
    {
    }

    function
    engines()
    {
        return
            array('MergeTree');
    }

    function
    logged_user()
    {
        global $b;
        $j = $b->credentials();
        return $j[1];
    }

    function
    tables_list()
    {
        $H = get_rows('SHOW TABLES');
        $I = array();
        foreach ($H
                 as $J) $I[$J['name']] = 'table';
        ksort($I);
        return $I;
    }

    function
    count_tables($l)
    {
        return
            array();
    }

    function
    table_status($C = "", $Oc = false)
    {
        global $g;
        $I = array();
        $S = get_rows("SELECT name, engine FROM system.tables WHERE database = " . q($g->_db));
        foreach ($S
                 as $Q) {
            $I[$Q['name']] = array('Name' => $Q['name'], 'Engine' => $Q['engine'],);
            if ($C === $Q['name']) return $I[$Q['name']];
        }
        return $I;
    }

    function
    is_view($R)
    {
        return
            false;
    }

    function
    fk_support($R)
    {
        return
            false;
    }

    function
    convert_field($p)
    {
    }

    function
    unconvert_field($p, $I)
    {
        if (in_array($p['type'], array("Int8", "Int16", "Int32", "Int64", "UInt8", "UInt16", "UInt32", "UInt64", "Float32", "Float64"))) return "to$p[type]($I)";
        return $I;
    }

    function
    fields($Q)
    {
        $I = array();
        $H = get_rows("SELECT name, type, default_expression FROM system.columns WHERE " . idf_escape('table') . " = " . q($Q));
        foreach ($H
                 as $J) {
            $T = trim($J['type']);
            $ff = strpos($T, 'Nullable(') === 0;
            $I[trim($J['name'])] = array("field" => trim($J['name']), "full_type" => $T, "type" => $T, "default" => trim($J['default_expression']), "null" => $ff, "auto_increment" => '0', "privileges" => array("insert" => 1, "select" => 1, "update" => 0),);
        }
        return $I;
    }

    function
    indexes($Q, $h = null)
    {
        return
            array();
    }

    function
    foreign_keys($Q)
    {
        return
            array();
    }

    function
    collations()
    {
        return
            array();
    }

    function
    information_schema($m)
    {
        return
            false;
    }

    function
    error()
    {
        global $g;
        return
            h($g->error);
    }

    function
    types()
    {
        return
            array();
    }

    function
    schemas()
    {
        return
            array();
    }

    function
    get_schema()
    {
        return "";
    }

    function
    set_schema($ah)
    {
        return
            true;
    }

    function
    auto_increment()
    {
        return '';
    }

    function
    last_id()
    {
        return
            0;
    }

    function
    support($Pc)
    {
        return
            preg_match("~^(columns|sql|status|table)$~", $Pc);
    }

    $y = "clickhouse";
    $U = array();
    $Ih = array();
    foreach (array('Numbers' => array("Int8" => 3, "Int16" => 5, "Int32" => 10, "Int64" => 19, "UInt8" => 3, "UInt16" => 5, "UInt32" => 10, "UInt64" => 20, "Float32" => 7, "Float64" => 16, 'Decimal' => 38, 'Decimal32' => 9, 'Decimal64' => 18, 'Decimal128' => 38), 'Date and time' => array("Date" => 13, "DateTime" => 20), 'Strings' => array("String" => 0), 'Binary' => array("FixedString" => 0),) as $z => $X) {
        $U += $X;
        $Ih[$z] = array_keys($X);
    }
    $Ji = array();
    $vf = array("=", "<", ">", "<=", ">=", "!=", "~", "!~", "LIKE", "LIKE %%", "IN", "IS NULL", "NOT LIKE", "NOT IN", "IS NOT NULL", "SQL");
    $kd = array();
    $qd = array("avg", "count", "count distinct", "max", "min", "sum");
    $mc = array();
}
$ec = array("server" => "MySQL") + $ec;
if (!defined("DRIVER")) {
    $hg = array("MySQLi", "MySQL", "PDO_MySQL");
    define("DRIVER", "server");
    if (extension_loaded("mysqli")) {
        class
        Min_DB
            extends
            MySQLi
        {
            var $extension = "MySQLi";

            function
            __construct()
            {
                parent::init();
            }

            function
            connect($N = "", $V = "", $F = "", $k = null, $dg = null, $uh = null)
            {
                global $b;
                mysqli_report(MYSQLI_REPORT_OFF);
                list($Ad, $dg) = explode(":", $N, 2);
                $Ch = $b->connectSsl();
                if ($Ch) $this->ssl_set($Ch['key'], $Ch['cert'], $Ch['ca'], '', '');
                $I = @$this->real_connect(($N != "" ? $Ad : ini_get("mysqli.default_host")), ($N . $V != "" ? $V : ini_get("mysqli.default_user")), ($N . $V . $F != "" ? $F : ini_get("mysqli.default_pw")), $k, (is_numeric($dg) ? $dg : ini_get("mysqli.default_port")), (!is_numeric($dg) ? $dg : $uh), ($Ch ? 64 : 0));
                $this->options(MYSQLI_OPT_LOCAL_INFILE, false);
                return $I;
            }

            function
            set_charset($cb)
            {
                if (parent::set_charset($cb)) return
                    true;
                parent::set_charset('utf8');
                return $this->query("SET NAMES $cb");
            }

            function
            result($G, $p = 0)
            {
                $H = $this->query($G);
                if (!$H) return
                    false;
                $J = $H->fetch_array();
                return $J[$p];
            }

            function
            quote($P)
            {
                return "'" . $this->escape_string($P) . "'";
            }
        }
    } elseif (extension_loaded("mysql") && !((ini_bool("sql.safe_mode") || ini_bool("mysql.allow_local_infile")) && extension_loaded("pdo_mysql"))) {
        class
        Min_DB
        {
            var $extension = "MySQL", $server_info, $affected_rows, $errno, $error, $_link, $_result;

            function
            connect($N, $V, $F)
            {
                if (ini_bool("mysql.allow_local_infile")) {
                    $this->error = sprintf('Disable %s or enable %s or %s extensions.', "'mysql.allow_local_infile'", "MySQLi", "PDO_MySQL");
                    return
                        false;
                }
                $this->_link = @mysql_connect(($N != "" ? $N : ini_get("mysql.default_host")), ("$N$V" != "" ? $V : ini_get("mysql.default_user")), ("$N$V$F" != "" ? $F : ini_get("mysql.default_password")), true, 131072);
                if ($this->_link) $this->server_info = mysql_get_server_info($this->_link); else$this->error = mysql_error();
                return (bool)$this->_link;
            }

            function
            set_charset($cb)
            {
                if (function_exists('mysql_set_charset')) {
                    if (mysql_set_charset($cb, $this->_link)) return
                        true;
                    mysql_set_charset('utf8', $this->_link);
                }
                return $this->query("SET NAMES $cb");
            }

            function
            quote($P)
            {
                return "'" . mysql_real_escape_string($P, $this->_link) . "'";
            }

            function
            select_db($k)
            {
                return
                    mysql_select_db($k, $this->_link);
            }

            function
            query($G, $Di = false)
            {
                $H = @($Di ? mysql_unbuffered_query($G, $this->_link) : mysql_query($G, $this->_link));
                $this->error = "";
                if (!$H) {
                    $this->errno = mysql_errno($this->_link);
                    $this->error = mysql_error($this->_link);
                    return
                        false;
                }
                if ($H === true) {
                    $this->affected_rows = mysql_affected_rows($this->_link);
                    $this->info = mysql_info($this->_link);
                    return
                        true;
                }
                return
                    new
                    Min_Result($H);
            }

            function
            multi_query($G)
            {
                return $this->_result = $this->query($G);
            }

            function
            store_result()
            {
                return $this->_result;
            }

            function
            next_result()
            {
                return
                    false;
            }

            function
            result($G, $p = 0)
            {
                $H = $this->query($G);
                if (!$H || !$H->num_rows) return
                    false;
                return
                    mysql_result($H->_result, 0, $p);
            }
        }

        class
        Min_Result
        {
            var $num_rows, $_result, $_offset = 0;

            function
            __construct($H)
            {
                $this->_result = $H;
                $this->num_rows = mysql_num_rows($H);
            }

            function
            fetch_assoc()
            {
                return
                    mysql_fetch_assoc($this->_result);
            }

            function
            fetch_row()
            {
                return
                    mysql_fetch_row($this->_result);
            }

            function
            fetch_field()
            {
                $I = mysql_fetch_field($this->_result, $this->_offset++);
                $I->orgtable = $I->table;
                $I->orgname = $I->name;
                $I->charsetnr = ($I->blob ? 63 : 0);
                return $I;
            }

            function
            __destruct()
            {
                mysql_free_result($this->_result);
            }
        }
    } elseif (extension_loaded("pdo_mysql")) {
        class
        Min_DB
            extends
            Min_PDO
        {
            var $extension = "PDO_MySQL";

            function
            connect($N, $V, $F)
            {
                global $b;
                $yf = array(PDO::MYSQL_ATTR_LOCAL_INFILE => false);
                $Ch = $b->connectSsl();
                if ($Ch) {
                    if (!empty($Ch['key'])) $yf[PDO::MYSQL_ATTR_SSL_KEY] = $Ch['key'];
                    if (!empty($Ch['cert'])) $yf[PDO::MYSQL_ATTR_SSL_CERT] = $Ch['cert'];
                    if (!empty($Ch['ca'])) $yf[PDO::MYSQL_ATTR_SSL_CA] = $Ch['ca'];
                }
                $this->dsn("mysql:charset=utf8;host=" . str_replace(":", ";unix_socket=", preg_replace('~:(\d)~', ';port=\1', $N)), $V, $F, $yf);
                return
                    true;
            }

            function
            set_charset($cb)
            {
                $this->query("SET NAMES $cb");
            }

            function
            select_db($k)
            {
                return $this->query("USE " . idf_escape($k));
            }

            function
            query($G, $Di = false)
            {
                $this->setAttribute(1000, !$Di);
                return
                    parent::query($G, $Di);
            }
        }
    }

    class
    Min_Driver
        extends
        Min_SQL
    {
        function
        insert($Q, $O)
        {
            return ($O ? parent::insert($Q, $O) : queries("INSERT INTO " . table($Q) . " ()\nVALUES ()"));
        }

        function
        insertUpdate($Q, $K, $kg)
        {
            $f = array_keys(reset($K));
            $ig = "INSERT INTO " . table($Q) . " (" . implode(", ", $f) . ") VALUES\n";
            $Wi = array();
            foreach ($f
                     as $z) $Wi[$z] = "$z = VALUES($z)";
            $Lh = "\nON DUPLICATE KEY UPDATE " . implode(", ", $Wi);
            $Wi = array();
            $te = 0;
            foreach ($K
                     as $O) {
                $Y = "(" . implode(", ", $O) . ")";
                if ($Wi && (strlen($ig) + $te + strlen($Y) + strlen($Lh) > 1e6)) {
                    if (!queries($ig . implode(",\n", $Wi) . $Lh)) return
                        false;
                    $Wi = array();
                    $te = 0;
                }
                $Wi[] = $Y;
                $te += strlen($Y) + 2;
            }
            return
                queries($ig . implode(",\n", $Wi) . $Lh);
        }

        function
        slowQuery($G, $gi)
        {
            if (min_version('5.7.8', '10.1.2')) {
                if (preg_match('~MariaDB~', $this->_conn->server_info)) return "SET STATEMENT max_statement_time=$gi FOR $G"; elseif (preg_match('~^(SELECT\b)(.+)~is', $G, $B)) return "$B[1] /*+ MAX_EXECUTION_TIME(" . ($gi * 1000) . ") */ $B[2]";
            }
        }

        function
        convertSearch($v, $X, $p)
        {
            return (preg_match('~char|text|enum|set~', $p["type"]) && !preg_match("~^utf8~", $p["collation"]) && preg_match('~[\x80-\xFF]~', $X['val']) ? "CONVERT($v USING " . charset($this->_conn) . ")" : $v);
        }

        function
        warnings()
        {
            $H = $this->_conn->query("SHOW WARNINGS");
            if ($H && $H->num_rows) {
                ob_start();
                select($H);
                return
                    ob_get_clean();
            }
        }

        function
        tableHelp($C)
        {
            $Ae = preg_match('~MariaDB~', $this->_conn->server_info);
            if (information_schema(DB)) return
                strtolower(($Ae ? "information-schema-$C-table/" : str_replace("_", "-", $C) . "-table.html"));
            if (DB == "mysql") return ($Ae ? "mysql$C-table/" : "system-database.html");
        }
    }

    function
    idf_escape($v)
    {
        return "`" . str_replace("`", "``", $v) . "`";
    }

    function
    table($v)
    {
        return
            idf_escape($v);
    }

    function
    connect()
    {
        global $b, $U, $Ih;
        $g = new
        Min_DB;
        $j = $b->credentials();
        if ($g->connect($j[0], $j[1], $j[2])) {
            $g->set_charset(charset($g));
            $g->query("SET sql_quote_show_create = 1, autocommit = 1");
            if (min_version('5.7.8', 10.2, $g)) {
                $Ih['Strings'][] = "json";
                $U["json"] = 4294967295;
            }
            return $g;
        }
        $I = $g->error;
        if (function_exists('iconv') && !is_utf8($I) && strlen($Yg = iconv("windows-1250", "utf-8", $I)) > strlen($I)) $I = $Yg;
        return $I;
    }

    function
    get_databases($ad)
    {
        $I = get_session("dbs");
        if ($I === null) {
            $G = (min_version(5) ? "SELECT SCHEMA_NAME FROM information_schema.SCHEMATA ORDER BY SCHEMA_NAME" : "SHOW DATABASES");
            $I = ($ad ? slow_query($G) : get_vals($G));
            restart_session();
            set_session("dbs", $I);
            stop_session();
        }
        return $I;
    }

    function
    limit($G, $Z, $_, $D = 0, $M = " ")
    {
        return " $G$Z" . ($_ !== null ? $M . "LIMIT $_" . ($D ? " OFFSET $D" : "") : "");
    }

    function
    limit1($Q, $G, $Z, $M = "\n")
    {
        return
            limit($G, $Z, 1, 0, $M);
    }

    function
    db_collation($m, $pb)
    {
        global $g;
        $I = null;
        $i = $g->result("SHOW CREATE DATABASE " . idf_escape($m), 1);
        if (preg_match('~ COLLATE ([^ ]+)~', $i, $B)) $I = $B[1]; elseif (preg_match('~ CHARACTER SET ([^ ]+)~', $i, $B)) $I = $pb[$B[1]][-1];
        return $I;
    }

    function
    engines()
    {
        $I = array();
        foreach (get_rows("SHOW ENGINES") as $J) {
            if (preg_match("~YES|DEFAULT~", $J["Support"])) $I[] = $J["Engine"];
        }
        return $I;
    }

    function
    logged_user()
    {
        global $g;
        return $g->result("SELECT USER()");
    }

    function
    tables_list()
    {
        return
            get_key_vals(min_version(5) ? "SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME" : "SHOW TABLES");
    }

    function
    count_tables($l)
    {
        $I = array();
        foreach ($l
                 as $m) $I[$m] = count(get_vals("SHOW TABLES IN " . idf_escape($m)));
        return $I;
    }

    function
    table_status($C = "", $Oc = false)
    {
        $I = array();
        foreach (get_rows($Oc && min_version(5) ? "SELECT TABLE_NAME AS Name, ENGINE AS Engine, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() " . ($C != "" ? "AND TABLE_NAME = " . q($C) : "ORDER BY Name") : "SHOW TABLE STATUS" . ($C != "" ? " LIKE " . q(addcslashes($C, "%_\\")) : "")) as $J) {
            if ($J["Engine"] == "InnoDB") $J["Comment"] = preg_replace('~(?:(.+); )?InnoDB free: .*~', '\1', $J["Comment"]);
            if (!isset($J["Engine"])) $J["Comment"] = "";
            if ($C != "") return $J;
            $I[$J["Name"]] = $J;
        }
        return $I;
    }

    function
    is_view($R)
    {
        return $R["Engine"] === null;
    }

    function
    fk_support($R)
    {
        return
            preg_match('~InnoDB|IBMDB2I~i', $R["Engine"]) || (preg_match('~NDB~i', $R["Engine"]) && min_version(5.6));
    }

    function
    fields($Q)
    {
        $I = array();
        foreach (get_rows("SHOW FULL COLUMNS FROM " . table($Q)) as $J) {
            preg_match('~^([^( ]+)(?:\((.+)\))?( unsigned)?( zerofill)?$~', $J["Type"], $B);
            $I[$J["Field"]] = array("field" => $J["Field"], "full_type" => $J["Type"], "type" => $B[1], "length" => $B[2], "unsigned" => ltrim($B[3] . $B[4]), "default" => ($J["Default"] != "" || preg_match("~char|set~", $B[1]) ? $J["Default"] : null), "null" => ($J["Null"] == "YES"), "auto_increment" => ($J["Extra"] == "auto_increment"), "on_update" => (preg_match('~^on update (.+)~i', $J["Extra"], $B) ? $B[1] : ""), "collation" => $J["Collation"], "privileges" => array_flip(preg_split('~, *~', $J["Privileges"])), "comment" => $J["Comment"], "primary" => ($J["Key"] == "PRI"), "generated" => preg_match('~^(VIRTUAL|PERSISTENT|STORED)~', $J["Extra"]),);
        }
        return $I;
    }

    function
    indexes($Q, $h = null)
    {
        $I = array();
        foreach (get_rows("SHOW INDEX FROM " . table($Q), $h) as $J) {
            $C = $J["Key_name"];
            $I[$C]["type"] = ($C == "PRIMARY" ? "PRIMARY" : ($J["Index_type"] == "FULLTEXT" ? "FULLTEXT" : ($J["Non_unique"] ? ($J["Index_type"] == "SPATIAL" ? "SPATIAL" : "INDEX") : "UNIQUE")));
            $I[$C]["columns"][] = $J["Column_name"];
            $I[$C]["lengths"][] = ($J["Index_type"] == "SPATIAL" ? null : $J["Sub_part"]);
            $I[$C]["descs"][] = null;
        }
        return $I;
    }

    function
    foreign_keys($Q)
    {
        global $g, $qf;
        static $ag = '(?:`(?:[^`]|``)+`|"(?:[^"]|"")+")';
        $I = array();
        $Fb = $g->result("SHOW CREATE TABLE " . table($Q), 1);
        if ($Fb) {
            preg_match_all("~CONSTRAINT ($ag) FOREIGN KEY ?\\(((?:$ag,? ?)+)\\) REFERENCES ($ag)(?:\\.($ag))? \\(((?:$ag,? ?)+)\\)(?: ON DELETE ($qf))?(?: ON UPDATE ($qf))?~", $Fb, $De, PREG_SET_ORDER);
            foreach ($De
                     as $B) {
                preg_match_all("~$ag~", $B[2], $wh);
                preg_match_all("~$ag~", $B[5], $Yh);
                $I[idf_unescape($B[1])] = array("db" => idf_unescape($B[4] != "" ? $B[3] : $B[4]), "table" => idf_unescape($B[4] != "" ? $B[4] : $B[3]), "source" => array_map('idf_unescape', $wh[0]), "target" => array_map('idf_unescape', $Yh[0]), "on_delete" => ($B[6] ? $B[6] : "RESTRICT"), "on_update" => ($B[7] ? $B[7] : "RESTRICT"),);
            }
        }
        return $I;
    }

    function
    view($C)
    {
        global $g;
        return
            array("select" => preg_replace('~^(?:[^`]|`[^`]*`)*\s+AS\s+~isU', '', $g->result("SHOW CREATE VIEW " . table($C), 1)));
    }

    function
    collations()
    {
        $I = array();
        foreach (get_rows("SHOW COLLATION") as $J) {
            if ($J["Default"]) $I[$J["Charset"]][-1] = $J["Collation"]; else$I[$J["Charset"]][] = $J["Collation"];
        }
        ksort($I);
        foreach ($I
                 as $z => $X) asort($I[$z]);
        return $I;
    }

    function
    information_schema($m)
    {
        return (min_version(5) && $m == "information_schema") || (min_version(5.5) && $m == "performance_schema");
    }

    function
    error()
    {
        global $g;
        return
            h(preg_replace('~^You have an error.*syntax to use~U', "Syntax error", $g->error));
    }

    function
    create_database($m, $d)
    {
        return
            queries("CREATE DATABASE " . idf_escape($m) . ($d ? " COLLATE " . q($d) : ""));
    }

    function
    drop_databases($l)
    {
        $I = apply_queries("DROP DATABASE", $l, 'idf_escape');
        restart_session();
        set_session("dbs", null);
        return $I;
    }

    function
    rename_database($C, $d)
    {
        $I = false;
        if (create_database($C, $d)) {
            $Kg = array();
            foreach (tables_list() as $Q => $T) $Kg[] = table($Q) . " TO " . idf_escape($C) . "." . table($Q);
            $I = (!$Kg || queries("RENAME TABLE " . implode(", ", $Kg)));
            if ($I) queries("DROP DATABASE " . idf_escape(DB));
            restart_session();
            set_session("dbs", null);
        }
        return $I;
    }

    function
    auto_increment()
    {
        $Na = " PRIMARY KEY";
        if ($_GET["create"] != "" && $_POST["auto_increment_col"]) {
            foreach (indexes($_GET["create"]) as $w) {
                if (in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"], $w["columns"], true)) {
                    $Na = "";
                    break;
                }
                if ($w["type"] == "PRIMARY") $Na = " UNIQUE";
            }
        }
        return " AUTO_INCREMENT$Na";
    }

    function
    alter_table($Q, $C, $q, $cd, $ub, $uc, $d, $Ma, $Uf)
    {
        $c = array();
        foreach ($q
                 as $p) $c[] = ($p[1] ? ($Q != "" ? ($p[0] != "" ? "CHANGE " . idf_escape($p[0]) : "ADD") : " ") . " " . implode($p[1]) . ($Q != "" ? $p[2] : "") : "DROP " . idf_escape($p[0]));
        $c = array_merge($c, $cd);
        $Fh = ($ub !== null ? " COMMENT=" . q($ub) : "") . ($uc ? " ENGINE=" . q($uc) : "") . ($d ? " COLLATE " . q($d) : "") . ($Ma != "" ? " AUTO_INCREMENT=$Ma" : "");
        if ($Q == "") return
            queries("CREATE TABLE " . table($C) . " (\n" . implode(",\n", $c) . "\n)$Fh$Uf");
        if ($Q != $C) $c[] = "RENAME TO " . table($C);
        if ($Fh) $c[] = ltrim($Fh);
        return ($c || $Uf ? queries("ALTER TABLE " . table($Q) . "\n" . implode(",\n", $c) . $Uf) : true);
    }

    function
    alter_indexes($Q, $c)
    {
        foreach ($c
                 as $z => $X) $c[$z] = ($X[2] == "DROP" ? "\nDROP INDEX " . idf_escape($X[1]) : "\nADD $X[0] " . ($X[0] == "PRIMARY" ? "KEY " : "") . ($X[1] != "" ? idf_escape($X[1]) . " " : "") . "(" . implode(", ", $X[2]) . ")");
        return
            queries("ALTER TABLE " . table($Q) . implode(",", $c));
    }

    function
    truncate_tables($S)
    {
        return
            apply_queries("TRUNCATE TABLE", $S);
    }

    function
    drop_views($bj)
    {
        return
            queries("DROP VIEW " . implode(", ", array_map('table', $bj)));
    }

    function
    drop_tables($S)
    {
        return
            queries("DROP TABLE " . implode(", ", array_map('table', $S)));
    }

    function
    move_tables($S, $bj, $Yh)
    {
        $Kg = array();
        foreach (array_merge($S, $bj) as $Q) $Kg[] = table($Q) . " TO " . idf_escape($Yh) . "." . table($Q);
        return
            queries("RENAME TABLE " . implode(", ", $Kg));
    }

    function
    copy_tables($S, $bj, $Yh)
    {
        queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");
        foreach ($S
                 as $Q) {
            $C = ($Yh == DB ? table("copy_$Q") : idf_escape($Yh) . "." . table($Q));
            if (($_POST["overwrite"] && !queries("\nDROP TABLE IF EXISTS $C")) || !queries("CREATE TABLE $C LIKE " . table($Q)) || !queries("INSERT INTO $C SELECT * FROM " . table($Q))) return
                false;
            foreach (get_rows("SHOW TRIGGERS LIKE " . q(addcslashes($Q, "%_\\"))) as $J) {
                $yi = $J["Trigger"];
                if (!queries("CREATE TRIGGER " . ($Yh == DB ? idf_escape("copy_$yi") : idf_escape($Yh) . "." . idf_escape($yi)) . " $J[Timing] $J[Event] ON $C FOR EACH ROW\n$J[Statement];")) return
                    false;
            }
        }
        foreach ($bj
                 as $Q) {
            $C = ($Yh == DB ? table("copy_$Q") : idf_escape($Yh) . "." . table($Q));
            $aj = view($Q);
            if (($_POST["overwrite"] && !queries("DROP VIEW IF EXISTS $C")) || !queries("CREATE VIEW $C AS $aj[select]")) return
                false;
        }
        return
            true;
    }

    function
    trigger($C)
    {
        if ($C == "") return
            array();
        $K = get_rows("SHOW TRIGGERS WHERE `Trigger` = " . q($C));
        return
            reset($K);
    }

    function
    triggers($Q)
    {
        $I = array();
        foreach (get_rows("SHOW TRIGGERS LIKE " . q(addcslashes($Q, "%_\\"))) as $J) $I[$J["Trigger"]] = array($J["Timing"], $J["Event"]);
        return $I;
    }

    function
    trigger_options()
    {
        return
            array("Timing" => array("BEFORE", "AFTER"), "Event" => array("INSERT", "UPDATE", "DELETE"), "Type" => array("FOR EACH ROW"),);
    }

    function
    routine($C, $T)
    {
        global $g, $wc, $Rd, $U;
        $Ca = array("bool", "boolean", "integer", "double precision", "real", "dec", "numeric", "fixed", "national char", "national varchar");
        $xh = "(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";
        $Ci = "((" . implode("|", array_merge(array_keys($U), $Ca)) . ")\\b(?:\\s*\\(((?:[^'\")]|$wc)++)\\))?\\s*(zerofill\\s*)?(unsigned(?:\\s+zerofill)?)?)(?:\\s*(?:CHARSET|CHARACTER\\s+SET)\\s*['\"]?([^'\"\\s,]+)['\"]?)?";
        $ag = "$xh*(" . ($T == "FUNCTION" ? "" : $Rd) . ")?\\s*(?:`((?:[^`]|``)*)`\\s*|\\b(\\S+)\\s+)$Ci";
        $i = $g->result("SHOW CREATE $T " . idf_escape($C), 2);
        preg_match("~\\(((?:$ag\\s*,?)*)\\)\\s*" . ($T == "FUNCTION" ? "RETURNS\\s+$Ci\\s+" : "") . "(.*)~is", $i, $B);
        $q = array();
        preg_match_all("~$ag\\s*,?~is", $B[1], $De, PREG_SET_ORDER);
        foreach ($De
                 as $Nf) {
            $C = str_replace("``", "`", $Nf[2]) . $Nf[3];
            $q[] = array("field" => $C, "type" => strtolower($Nf[5]), "length" => preg_replace_callback("~$wc~s", 'normalize_enum', $Nf[6]), "unsigned" => strtolower(preg_replace('~\s+~', ' ', trim("$Nf[8] $Nf[7]"))), "null" => 1, "full_type" => $Nf[4], "inout" => strtoupper($Nf[1]), "collation" => strtolower($Nf[9]),);
        }
        if ($T != "FUNCTION") return
            array("fields" => $q, "definition" => $B[11]);
        return
            array("fields" => $q, "returns" => array("type" => $B[12], "length" => $B[13], "unsigned" => $B[15], "collation" => $B[16]), "definition" => $B[17], "language" => "SQL",);
    }

    function
    routines()
    {
        return
            get_rows("SELECT ROUTINE_NAME AS SPECIFIC_NAME, ROUTINE_NAME, ROUTINE_TYPE, DTD_IDENTIFIER FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = " . q(DB));
    }

    function
    routine_languages()
    {
        return
            array();
    }

    function
    routine_id($C, $J)
    {
        return
            idf_escape($C);
    }

    function
    last_id()
    {
        global $g;
        return $g->result("SELECT LAST_INSERT_ID()");
    }

    function
    explain($g, $G)
    {
        return $g->query("EXPLAIN " . (min_version(5.1) ? "PARTITIONS " : "") . $G);
    }

    function
    found_rows($R, $Z)
    {
        return ($Z || $R["Engine"] != "InnoDB" ? null : $R["Rows"]);
    }

    function
    types()
    {
        return
            array();
    }

    function
    schemas()
    {
        return
            array();
    }

    function
    get_schema()
    {
        return "";
    }

    function
    set_schema($ah)
    {
        return
            true;
    }

    function
    create_sql($Q, $Ma, $Jh)
    {
        global $g;
        $I = $g->result("SHOW CREATE TABLE " . table($Q), 1);
        if (!$Ma) $I = preg_replace('~ AUTO_INCREMENT=\d+~', '', $I);
        return $I;
    }

    function
    truncate_sql($Q)
    {
        return "TRUNCATE " . table($Q);
    }

    function
    use_sql($k)
    {
        return "USE " . idf_escape($k);
    }

    function
    trigger_sql($Q)
    {
        $I = "";
        foreach (get_rows("SHOW TRIGGERS LIKE " . q(addcslashes($Q, "%_\\")), null, "-- ") as $J) $I .= "\nCREATE TRIGGER " . idf_escape($J["Trigger"]) . " $J[Timing] $J[Event] ON " . table($J["Table"]) . " FOR EACH ROW\n$J[Statement];;\n";
        return $I;
    }

    function
    show_variables()
    {
        return
            get_key_vals("SHOW VARIABLES");
    }

    function
    process_list()
    {
        return
            get_rows("SHOW FULL PROCESSLIST");
    }

    function
    show_status()
    {
        return
            get_key_vals("SHOW STATUS");
    }

    function
    convert_field($p)
    {
        if (preg_match("~binary~", $p["type"])) return "HEX(" . idf_escape($p["field"]) . ")";
        if ($p["type"] == "bit") return "BIN(" . idf_escape($p["field"]) . " + 0)";
        if (preg_match("~geometry|point|linestring|polygon~", $p["type"])) return (min_version(8) ? "ST_" : "") . "AsWKT(" . idf_escape($p["field"]) . ")";
    }

    function
    unconvert_field($p, $I)
    {
        if (preg_match("~binary~", $p["type"])) $I = "UNHEX($I)";
        if ($p["type"] == "bit") $I = "CONV($I, 2, 10) + 0";
        if (preg_match("~geometry|point|linestring|polygon~", $p["type"])) $I = (min_version(8) ? "ST_" : "") . "GeomFromText($I, SRID($p[field]))";
        return $I;
    }

    function
    support($Pc)
    {
        return !preg_match("~scheme|sequence|type|view_trigger|materializedview" . (min_version(8) ? "" : "|descidx" . (min_version(5.1) ? "" : "|event|partitioning" . (min_version(5) ? "" : "|routine|trigger|view"))) . "~", $Pc);
    }

    function
    kill_process($X)
    {
        return
            queries("KILL " . number($X));
    }

    function
    connection_id()
    {
        return "SELECT CONNECTION_ID()";
    }

    function
    max_connections()
    {
        global $g;
        return $g->result("SELECT @@max_connections");
    }

    $y = "sql";
    $U = array();
    $Ih = array();
    foreach (array('Numbers' => array("tinyint" => 3, "smallint" => 5, "mediumint" => 8, "int" => 10, "bigint" => 20, "decimal" => 66, "float" => 12, "double" => 21), 'Date and time' => array("date" => 10, "datetime" => 19, "timestamp" => 19, "time" => 10, "year" => 4), 'Strings' => array("char" => 255, "varchar" => 65535, "tinytext" => 255, "text" => 65535, "mediumtext" => 16777215, "longtext" => 4294967295), 'Lists' => array("enum" => 65535, "set" => 64), 'Binary' => array("bit" => 20, "binary" => 255, "varbinary" => 65535, "tinyblob" => 255, "blob" => 65535, "mediumblob" => 16777215, "longblob" => 4294967295), 'Geometry' => array("geometry" => 0, "point" => 0, "linestring" => 0, "polygon" => 0, "multipoint" => 0, "multilinestring" => 0, "multipolygon" => 0, "geometrycollection" => 0),) as $z => $X) {
        $U += $X;
        $Ih[$z] = array_keys($X);
    }
    $Ji = array("unsigned", "zerofill", "unsigned zerofill");
    $vf = array("=", "<", ">", "<=", ">=", "!=", "LIKE", "LIKE %%", "REGEXP", "IN", "FIND_IN_SET", "IS NULL", "NOT LIKE", "NOT REGEXP", "NOT IN", "IS NOT NULL", "SQL");
    $kd = array("char_length", "date", "from_unixtime", "lower", "round", "floor", "ceil", "sec_to_time", "time_to_sec", "upper");
    $qd = array("avg", "count", "count distinct", "group_concat", "max", "min", "sum");
    $mc = array(array("char" => "md5/sha1/password/encrypt/uuid", "binary" => "md5/sha1", "date|time" => "now",), array(number_type() => "+/-", "date" => "+ interval/- interval", "time" => "addtime/subtime", "char|text" => "concat",));
}
define("SERVER", $_GET[DRIVER]);
define("DB", $_GET["db"]);
define("ME", preg_replace('~^[^?]*/([^?]*).*~', '\1', $_SERVER["REQUEST_URI"]) . '?' . (sid() ? SID . '&' : '') . (SERVER !== null ? DRIVER . "=" . urlencode(SERVER) . '&' : '') . (isset($_GET["username"]) ? "username=" . urlencode($_GET["username"]) . '&' : '') . (DB != "" ? 'db=' . urlencode(DB) . '&' . (isset($_GET["ns"]) ? "ns=" . urlencode($_GET["ns"]) . "&" : "") : ''));
$ia = "4.7.3";

class
Adminer
{
    var $operators;

    function
    credentials()
    {
        return
            array(SERVER, $_GET["username"], get_password());
    }

    function
    connectSsl()
    {
    }

    function
    permanentLogin($i = false)
    {
        return
            password_file($i);
    }

    function
    bruteForceKey()
    {
        return $_SERVER["REMOTE_ADDR"];
    }

    function
    database()
    {
        return
            DB;
    }

    function
    schemas()
    {
        return
            schemas();
    }

    function
    queryTimeout()
    {
        return
            2;
    }

    function
    headers()
    {
    }

    function
    csp()
    {
        return
            csp();
    }

    function
    head()
    {
        return
            true;
    }

    function
    css()
    {
        $I = array();
        $Uc = "adminer.css";
        if (file_exists($Uc)) $I[] = "$Uc?v=" . crc32(file_get_contents($Uc));
        return $I;
    }

    function
    loginForm()
    {
        global $ec;
        echo "<table cellspacing='0' class='layout'>\n", $this->loginFormField('driver', '<tr><th>' . 'System' . '<td>', html_select("auth[driver]", $ec, DRIVER, "loginDriver(this);") . "\n"), $this->loginFormField('server', '<tr><th>' . 'Server' . '<td>', '<input name="auth[server]" value="' . h(SERVER) . '" title="hostname[:port]" placeholder="localhost" autocapitalize="off">' . "\n"), $this->loginFormField('username', '<tr><th>' . 'Username' . '<td>', '<input name="auth[username]" id="username" value="' . h($_GET["username"]) . '" autocomplete="username" autocapitalize="off">' . script("focus(qs('#username')); qs('#username').form['auth[driver]'].onchange();")), $this->loginFormField('password', '<tr><th>' . 'Password' . '<td>', '<input type="password" name="auth[password]" autocomplete="current-password">' . "\n"), $this->loginFormField('db', '<tr><th>' . 'Database' . '<td>', '<input name="auth[db]" value="' . h($_GET["db"]) . '" autocapitalize="off">' . "\n"), "</table>\n", "<p><input type='submit' value='" . 'Login' . "'>\n", checkbox("auth[permanent]", 1, $_COOKIE["adminer_permanent"], 'Permanent login') . "\n";
    }

    function
    loginFormField($C, $xd, $Y)
    {
        return $xd . $Y;
    }

    function
    login($ye, $F)
    {
        if ($F == "") return
            sprintf('Adminer does not support accessing a database without a password, <a href="https://www.adminer.org/en/password/"%s>more information</a>.', target_blank());
        return
            true;
    }

    function
    fieldName($p, $_f = 0)
    {
        return '<span title="' . h($p["full_type"]) . '">' . h($p["field"]) . '</span>';
    }

    function
    selectLinks($Ph, $O = "")
    {
        global $y, $n;
        echo '<p class="links">';
        $we = array("select" => 'Select data');
        if (support("table") || support("indexes")) $we["table"] = 'Show structure';
        if (support("table")) {
            if (is_view($Ph)) $we["view"] = 'Alter view'; else$we["create"] = 'Alter table';
        }
        if ($O !== null) $we["edit"] = 'New item';
        $C = $Ph["Name"];
        foreach ($we
                 as $z => $X) echo " <a href='" . h(ME) . "$z=" . urlencode($C) . ($z == "edit" ? $O : "") . "'" . bold(isset($_GET[$z])) . ">$X</a>";
        echo
        doc_link(array($y => $n->tableHelp($C)), "?"), "\n";
    }

    function
    foreignKeys($Q)
    {
        return
            foreign_keys($Q);
    }

    function
    backwardKeys($Q, $Oh)
    {
        return
            array();
    }

    function
    backwardKeysPrint($Pa, $J)
    {
    }

    function
    selectQuery($G, $Dh, $Nc = false)
    {
        global $y, $n;
        $I = "</p>\n";
        if (!$Nc && ($ej = $n->warnings())) {
            $u = "warnings";
            $I = ", <a href='#$u'>" . 'Warnings' . "</a>" . script("qsl('a').onclick = partial(toggle, '$u');", "") . "$I<div id='$u' class='hidden'>\n$ej</div>\n";
        }
        return "<p><code class='jush-$y'>" . h(str_replace("\n", " ", $G)) . "</code> <span class='time'>(" . format_time($Dh) . ")</span>" . (support("sql") ? " <a href='" . h(ME) . "sql=" . urlencode($G) . "'>" . 'Edit' . "</a>" : "") . $I;
    }

    function
    sqlCommandQuery($G)
    {
        return
            shorten_utf8(trim($G), 1000);
    }

    function
    rowDescription($Q)
    {
        return "";
    }

    function
    rowDescriptions($K, $dd)
    {
        return $K;
    }

    function
    selectLink($X, $p)
    {
    }

    function
    selectVal($X, $A, $p, $Hf)
    {
        $I = ($X === null ? "<i>NULL</i>" : (preg_match("~char|binary|boolean~", $p["type"]) && !preg_match("~var~", $p["type"]) ? "<code>$X</code>" : $X));
        if (preg_match('~blob|bytea|raw|file~', $p["type"]) && !is_utf8($X)) $I = "<i>" . lang(array('%d byte', '%d bytes'), strlen($Hf)) . "</i>";
        if (preg_match('~json~', $p["type"])) $I = "<code class='jush-js'>$I</code>";
        return ($A ? "<a href='" . h($A) . "'" . (is_url($A) ? target_blank() : "") . ">$I</a>" : $I);
    }

    function
    editVal($X, $p)
    {
        return $X;
    }

    function
    tableStructurePrint($q)
    {
        echo "<div class='scrollable'>\n", "<table cellspacing='0' class='nowrap'>\n", "<thead><tr><th>" . 'Column' . "<td>" . 'Type' . (support("comment") ? "<td>" . 'Comment' : "") . "</thead>\n";
        foreach ($q
                 as $p) {
            echo "<tr" . odd() . "><th>" . h($p["field"]), "<td><span title='" . h($p["collation"]) . "'>" . h($p["full_type"]) . "</span>", ($p["null"] ? " <i>NULL</i>" : ""), ($p["auto_increment"] ? " <i>" . 'Auto Increment' . "</i>" : ""), (isset($p["default"]) ? " <span title='" . 'Default value' . "'>[<b>" . h($p["default"]) . "</b>]</span>" : ""), (support("comment") ? "<td>" . h($p["comment"]) : ""), "\n";
        }
        echo "</table>\n", "</div>\n";
    }

    function
    tableIndexesPrint($x)
    {
        echo "<table cellspacing='0'>\n";
        foreach ($x
                 as $C => $w) {
            ksort($w["columns"]);
            $mg = array();
            foreach ($w["columns"] as $z => $X) $mg[] = "<i>" . h($X) . "</i>" . ($w["lengths"][$z] ? "(" . $w["lengths"][$z] . ")" : "") . ($w["descs"][$z] ? " DESC" : "");
            echo "<tr title='" . h($C) . "'><th>$w[type]<td>" . implode(", ", $mg) . "\n";
        }
        echo "</table>\n";
    }

    function
    selectColumnsPrint($L, $f)
    {
        global $kd, $qd;
        print_fieldset("select", 'Select', $L);
        $t = 0;
        $L[""] = array();
        foreach ($L
                 as $z => $X) {
            $X = $_GET["columns"][$z];
            $e = select_input(" name='columns[$t][col]'", $f, $X["col"], ($z !== "" ? "selectFieldChange" : "selectAddRow"));
            echo "<div>" . ($kd || $qd ? "<select name='columns[$t][fun]'>" . optionlist(array(-1 => "") + array_filter(array('Functions' => $kd, 'Aggregation' => $qd)), $X["fun"]) . "</select>" . on_help("getTarget(event).value && getTarget(event).value.replace(/ |\$/, '(') + ')'", 1) . script("qsl('select').onchange = function () { helpClose();" . ($z !== "" ? "" : " qsl('select, input', this.parentNode).onchange();") . " };", "") . "($e)" : $e) . "</div>\n";
            $t++;
        }
        echo "</div></fieldset>\n";
    }

    function
    selectSearchPrint($Z, $f, $x)
    {
        print_fieldset("search", 'Search', $Z);
        foreach ($x
                 as $t => $w) {
            if ($w["type"] == "FULLTEXT") {
                echo "<div>(<i>" . implode("</i>, <i>", array_map('h', $w["columns"])) . "</i>) AGAINST", " <input type='search' name='fulltext[$t]' value='" . h($_GET["fulltext"][$t]) . "'>", script("qsl('input').oninput = selectFieldChange;", ""), checkbox("boolean[$t]", 1, isset($_GET["boolean"][$t]), "BOOL"), "</div>\n";
            }
        }
        $bb = "this.parentNode.firstChild.onchange();";
        foreach (array_merge((array)$_GET["where"], array(array())) as $t => $X) {
            if (!$X || ("$X[col]$X[val]" != "" && in_array($X["op"], $this->operators))) {
                echo "<div>" . select_input(" name='where[$t][col]'", $f, $X["col"], ($X ? "selectFieldChange" : "selectAddRow"), "(" . 'anywhere' . ")"), html_select("where[$t][op]", $this->operators, $X["op"], $bb), "<input type='search' name='where[$t][val]' value='" . h($X["val"]) . "'>", script("mixin(qsl('input'), {oninput: function () { $bb }, onkeydown: selectSearchKeydown, onsearch: selectSearchSearch});", ""), "</div>\n";
            }
        }
        echo "</div></fieldset>\n";
    }

    function
    selectOrderPrint($_f, $f, $x)
    {
        print_fieldset("sort", 'Sort', $_f);
        $t = 0;
        foreach ((array)$_GET["order"] as $z => $X) {
            if ($X != "") {
                echo "<div>" . select_input(" name='order[$t]'", $f, $X, "selectFieldChange"), checkbox("desc[$t]", 1, isset($_GET["desc"][$z]), 'descending') . "</div>\n";
                $t++;
            }
        }
        echo "<div>" . select_input(" name='order[$t]'", $f, "", "selectAddRow"), checkbox("desc[$t]", 1, false, 'descending') . "</div>\n", "</div></fieldset>\n";
    }

    function
    selectLimitPrint($_)
    {
        echo "<fieldset><legend>" . 'Limit' . "</legend><div>";
        echo "<input type='number' name='limit' class='size' value='" . h($_) . "'>", script("qsl('input').oninput = selectFieldChange;", ""), "</div></fieldset>\n";
    }

    function
    selectLengthPrint($ei)
    {
        if ($ei !== null) {
            echo "<fieldset><legend>" . 'Text length' . "</legend><div>", "<input type='number' name='text_length' class='size' value='" . h($ei) . "'>", "</div></fieldset>\n";
        }
    }

    function
    selectActionPrint($x)
    {
        echo "<fieldset><legend>" . 'Action' . "</legend><div>", "<input type='submit' value='" . 'Select' . "'>", " <span id='noindex' title='" . 'Full table scan' . "'></span>", "<script" . nonce() . ">\n", "var indexColumns = ";
        $f = array();
        foreach ($x
                 as $w) {
            $Kb = reset($w["columns"]);
            if ($w["type"] != "FULLTEXT" && $Kb) $f[$Kb] = 1;
        }
        $f[""] = 1;
        foreach ($f
                 as $z => $X) json_row($z);
        echo ";\n", "selectFieldChange.call(qs('#form')['select']);\n", "</script>\n", "</div></fieldset>\n";
    }

    function
    selectCommandPrint()
    {
        return !information_schema(DB);
    }

    function
    selectImportPrint()
    {
        return !information_schema(DB);
    }

    function
    selectEmailPrint($rc, $f)
    {
    }

    function
    selectColumnsProcess($f, $x)
    {
        global $kd, $qd;
        $L = array();
        $nd = array();
        foreach ((array)$_GET["columns"] as $z => $X) {
            if ($X["fun"] == "count" || ($X["col"] != "" && (!$X["fun"] || in_array($X["fun"], $kd) || in_array($X["fun"], $qd)))) {
                $L[$z] = apply_sql_function($X["fun"], ($X["col"] != "" ? idf_escape($X["col"]) : "*"));
                if (!in_array($X["fun"], $qd)) $nd[] = $L[$z];
            }
        }
        return
            array($L, $nd);
    }

    function
    selectSearchProcess($q, $x)
    {
        global $g, $n;
        $I = array();
        foreach ($x
                 as $t => $w) {
            if ($w["type"] == "FULLTEXT" && $_GET["fulltext"][$t] != "") $I[] = "MATCH (" . implode(", ", array_map('idf_escape', $w["columns"])) . ") AGAINST (" . q($_GET["fulltext"][$t]) . (isset($_GET["boolean"][$t]) ? " IN BOOLEAN MODE" : "") . ")";
        }
        foreach ((array)$_GET["where"] as $z => $X) {
            if ("$X[col]$X[val]" != "" && in_array($X["op"], $this->operators)) {
                $ig = "";
                $wb = " $X[op]";
                if (preg_match('~IN$~', $X["op"])) {
                    $Hd = process_length($X["val"]);
                    $wb .= " " . ($Hd != "" ? $Hd : "(NULL)");
                } elseif ($X["op"] == "SQL") $wb = " $X[val]";
                elseif ($X["op"] == "LIKE %%") $wb = " LIKE " . $this->processInput($q[$X["col"]], "%$X[val]%");
                elseif ($X["op"] == "ILIKE %%") $wb = " ILIKE " . $this->processInput($q[$X["col"]], "%$X[val]%");
                elseif ($X["op"] == "FIND_IN_SET") {
                    $ig = "$X[op](" . q($X["val"]) . ", ";
                    $wb = ")";
                } elseif (!preg_match('~NULL$~', $X["op"])) $wb .= " " . $this->processInput($q[$X["col"]], $X["val"]);
                if ($X["col"] != "") $I[] = $ig . $n->convertSearch(idf_escape($X["col"]), $X, $q[$X["col"]]) . $wb; else {
                    $rb = array();
                    foreach ($q
                             as $C => $p) {
                        if ((preg_match('~^[-\d.' . (preg_match('~IN$~', $X["op"]) ? ',' : '') . ']+$~', $X["val"]) || !preg_match('~' . number_type() . '|bit~', $p["type"])) && (!preg_match("~[\x80-\xFF]~", $X["val"]) || preg_match('~char|text|enum|set~', $p["type"]))) $rb[] = $ig . $n->convertSearch(idf_escape($C), $X, $p) . $wb;
                    }
                    $I[] = ($rb ? "(" . implode(" OR ", $rb) . ")" : "1 = 0");
                }
            }
        }
        return $I;
    }

    function
    processInput($p, $Y, $s = "")
    {
        if ($s == "SQL") return $Y;
        $C = $p["field"];
        $I = q($Y);
        if (preg_match('~^(now|getdate|uuid)$~', $s)) $I = "$s()"; elseif (preg_match('~^current_(date|timestamp)$~', $s)) $I = $s;
        elseif (preg_match('~^([+-]|\|\|)$~', $s)) $I = idf_escape($C) . " $s $I";
        elseif (preg_match('~^[+-] interval$~', $s)) $I = idf_escape($C) . " $s " . (preg_match("~^(\\d+|'[0-9.: -]') [A-Z_]+\$~i", $Y) ? $Y : $I);
        elseif (preg_match('~^(addtime|subtime|concat)$~', $s)) $I = "$s(" . idf_escape($C) . ", $I)";
        elseif (preg_match('~^(md5|sha1|password|encrypt)$~', $s)) $I = "$s($I)";
        return
            unconvert_field($p, $I);
    }

    function
    selectOrderProcess($q, $x)
    {
        $I = array();
        foreach ((array)$_GET["order"] as $z => $X) {
            if ($X != "") $I[] = (preg_match('~^((COUNT\(DISTINCT |[A-Z0-9_]+\()(`(?:[^`]|``)+`|"(?:[^"]|"")+")\)|COUNT\(\*\))$~', $X) ? $X : idf_escape($X)) . (isset($_GET["desc"][$z]) ? " DESC" : "");
        }
        return $I;
    }

    function
    selectLimitProcess()
    {
        return (isset($_GET["limit"]) ? $_GET["limit"] : "50");
    }

    function
    selectLengthProcess()
    {
        return (isset($_GET["text_length"]) ? $_GET["text_length"] : "100");
    }

    function
    selectEmailProcess($Z, $dd)
    {
        return
            false;
    }

    function
    selectQueryBuild($L, $Z, $nd, $_f, $_, $E)
    {
        return "";
    }

    function
    messageQuery($G, $fi, $Nc = false)
    {
        global $y, $n;
        restart_session();
        $yd =& get_session("queries");
        if (!$yd[$_GET["db"]]) $yd[$_GET["db"]] = array();
        if (strlen($G) > 1e6) $G = preg_replace('~[\x80-\xFF]+$~', '', substr($G, 0, 1e6)) . "\n…";
        $yd[$_GET["db"]][] = array($G, time(), $fi);
        $Ah = "sql-" . count($yd[$_GET["db"]]);
        $I = "<a href='#$Ah' class='toggle'>" . 'SQL command' . "</a>\n";
        if (!$Nc && ($ej = $n->warnings())) {
            $u = "warnings-" . count($yd[$_GET["db"]]);
            $I = "<a href='#$u' class='toggle'>" . 'Warnings' . "</a>, $I<div id='$u' class='hidden'>\n$ej</div>\n";
        }
        return " <span class='time'>" . @date("H:i:s") . "</span>" . " $I<div id='$Ah' class='hidden'><pre><code class='jush-$y'>" . shorten_utf8($G, 1000) . "</code></pre>" . ($fi ? " <span class='time'>($fi)</span>" : '') . (support("sql") ? '<p><a href="' . h(str_replace("db=" . urlencode(DB), "db=" . urlencode($_GET["db"]), ME) . 'sql=&history=' . (count($yd[$_GET["db"]]) - 1)) . '">' . 'Edit' . '</a>' : '') . '</div>';
    }

    function
    editFunctions($p)
    {
        global $mc;
        $I = ($p["null"] ? "NULL/" : "");
        foreach ($mc
                 as $z => $kd) {
            if (!$z || (!isset($_GET["call"]) && (isset($_GET["select"]) || where($_GET)))) {
                foreach ($kd
                         as $ag => $X) {
                    if (!$ag || preg_match("~$ag~", $p["type"])) $I .= "/$X";
                }
                if ($z && !preg_match('~set|blob|bytea|raw|file~', $p["type"])) $I .= "/SQL";
            }
        }
        if ($p["auto_increment"] && !isset($_GET["select"]) && !where($_GET)) $I = 'Auto Increment';
        return
            explode("/", $I);
    }

    function
    editInput($Q, $p, $Ja, $Y)
    {
        if ($p["type"] == "enum") return (isset($_GET["select"]) ? "<label><input type='radio'$Ja value='-1' checked><i>" . 'original' . "</i></label> " : "") . ($p["null"] ? "<label><input type='radio'$Ja value=''" . ($Y !== null || isset($_GET["select"]) ? "" : " checked") . "><i>NULL</i></label> " : "") . enum_input("radio", $Ja, $p, $Y, 0);
        return "";
    }

    function
    editHint($Q, $p, $Y)
    {
        return "";
    }

    function
    dumpOutput()
    {
        $I = array('text' => 'open', 'file' => 'save');
        if (function_exists('gzencode')) $I['gz'] = 'gzip';
        return $I;
    }

    function
    dumpFormat()
    {
        return
            array('sql' => 'SQL', 'csv' => 'CSV,', 'csv;' => 'CSV;', 'tsv' => 'TSV');
    }

    function
    dumpDatabase($m)
    {
    }

    function
    dumpTable($Q, $Jh, $ae = 0)
    {
        if ($_POST["format"] != "sql") {
            echo "\xef\xbb\xbf";
            if ($Jh) dump_csv(array_keys(fields($Q)));
        } else {
            if ($ae == 2) {
                $q = array();
                foreach (fields($Q) as $C => $p) $q[] = idf_escape($C) . " $p[full_type]";
                $i = "CREATE TABLE " . table($Q) . " (" . implode(", ", $q) . ")";
            } else$i = create_sql($Q, $_POST["auto_increment"], $Jh);
            set_utf8mb4($i);
            if ($Jh && $i) {
                if ($Jh == "DROP+CREATE" || $ae == 1) echo "DROP " . ($ae == 2 ? "VIEW" : "TABLE") . " IF EXISTS " . table($Q) . ";\n";
                if ($ae == 1) $i = remove_definer($i);
                echo "$i;\n\n";
            }
        }
    }

    function
    dumpData($Q, $Jh, $G)
    {
        global $g, $y;
        $Fe = ($y == "sqlite" ? 0 : 1048576);
        if ($Jh) {
            if ($_POST["format"] == "sql") {
                if ($Jh == "TRUNCATE+INSERT") echo
                    truncate_sql($Q) . ";\n";
                $q = fields($Q);
            }
            $H = $g->query($G, 1);
            if ($H) {
                $Td = "";
                $Ya = "";
                $he = array();
                $Lh = "";
                $Qc = ($Q != '' ? 'fetch_assoc' : 'fetch_row');
                while ($J = $H->$Qc()) {
                    if (!$he) {
                        $Wi = array();
                        foreach ($J
                                 as $X) {
                            $p = $H->fetch_field();
                            $he[] = $p->name;
                            $z = idf_escape($p->name);
                            $Wi[] = "$z = VALUES($z)";
                        }
                        $Lh = ($Jh == "INSERT+UPDATE" ? "\nON DUPLICATE KEY UPDATE " . implode(", ", $Wi) : "") . ";\n";
                    }
                    if ($_POST["format"] != "sql") {
                        if ($Jh == "table") {
                            dump_csv($he);
                            $Jh = "INSERT";
                        }
                        dump_csv($J);
                    } else {
                        if (!$Td) $Td = "INSERT INTO " . table($Q) . " (" . implode(", ", array_map('idf_escape', $he)) . ") VALUES";
                        foreach ($J
                                 as $z => $X) {
                            $p = $q[$z];
                            $J[$z] = ($X !== null ? unconvert_field($p, preg_match(number_type(), $p["type"]) && !preg_match('~\[~', $p["full_type"]) && is_numeric($X) ? $X : q(($X === false ? 0 : $X))) : "NULL");
                        }
                        $Yg = ($Fe ? "\n" : " ") . "(" . implode(",\t", $J) . ")";
                        if (!$Ya) $Ya = $Td . $Yg; elseif (strlen($Ya) + 4 + strlen($Yg) + strlen($Lh) < $Fe) $Ya .= ",$Yg";
                        else {
                            echo $Ya . $Lh;
                            $Ya = $Td . $Yg;
                        }
                    }
                }
                if ($Ya) echo $Ya . $Lh;
            } elseif ($_POST["format"] == "sql") echo "-- " . str_replace("\n", " ", $g->error) . "\n";
        }
    }

    function
    dumpFilename($Cd)
    {
        return
            friendly_url($Cd != "" ? $Cd : (SERVER != "" ? SERVER : "localhost"));
    }

    function
    dumpHeaders($Cd, $Ue = false)
    {
        $Kf = $_POST["output"];
        $Ic = (preg_match('~sql~', $_POST["format"]) ? "sql" : ($Ue ? "tar" : "csv"));
        header("Content-Type: " . ($Kf == "gz" ? "application/x-gzip" : ($Ic == "tar" ? "application/x-tar" : ($Ic == "sql" || $Kf != "file" ? "text/plain" : "text/csv") . "; charset=utf-8")));
        if ($Kf == "gz") ob_start('ob_gzencode', 1e6);
        return $Ic;
    }

    function
    importServerPath()
    {
        return "adminer.sql";
    }

    function
    homepage()
    {
        echo '<p class="links">' . ($_GET["ns"] == "" && support("database") ? '<a href="' . h(ME) . 'database=">' . 'Alter database' . "</a>\n" : ""), (support("scheme") ? "<a href='" . h(ME) . "scheme='>" . ($_GET["ns"] != "" ? 'Alter schema' : 'Create schema') . "</a>\n" : ""), ($_GET["ns"] !== "" ? '<a href="' . h(ME) . 'schema=">' . 'Database schema' . "</a>\n" : ""), (support("privileges") ? "<a href='" . h(ME) . "privileges='>" . 'Privileges' . "</a>\n" : "");
        return
            true;
    }

    function
    navigation($Te)
    {
        global $ia, $y, $ec, $g;
        echo '<h1>
', $this->name(), ' <span class="version">', $ia, '</span>
<a href="https://www.adminer.org/#download"', target_blank(), ' id="version">', (version_compare($ia, $_COOKIE["adminer_version"]) < 0 ? h($_COOKIE["adminer_version"]) : ""), '</a>
</h1>
';
        if ($Te == "auth") {
            $Kf = "";
            foreach ((array)$_SESSION["pwds"] as $Yi => $mh) {
                foreach ($mh
                         as $N => $Ti) {
                    foreach ($Ti
                             as $V => $F) {
                        if ($F !== null) {
                            $Qb = $_SESSION["db"][$Yi][$N][$V];
                            foreach (($Qb ? array_keys($Qb) : array("")) as $m) $Kf .= "<li><a href='" . h(auth_url($Yi, $N, $V, $m)) . "'>($ec[$Yi]) " . h($V . ($N != "" ? "@" . $this->serverName($N) : "") . ($m != "" ? " - $m" : "")) . "</a>\n";
                        }
                    }
                }
            }
            if ($Kf) echo "<ul id='logins'>\n$Kf</ul>\n" . script("mixin(qs('#logins'), {onmouseover: menuOver, onmouseout: menuOut});");
        } else {
            if ($_GET["ns"] !== "" && !$Te && DB != "") {
                $g->select_db(DB);
                $S = table_status('', true);
            }
            echo
            script_src(preg_replace("~\\?.*~", "", ME) . "?file=jush.js&version=4.7.3");
            if (support("sql")) {
                echo '<script', nonce(), '>
';
                if ($S) {
                    $we = array();
                    foreach ($S
                             as $Q => $T) $we[] = preg_quote($Q, '/');
                    echo "var jushLinks = { $y: [ '" . js_escape(ME) . (support("table") ? "table=" : "select=") . "\$&', /\\b(" . implode("|", $we) . ")\\b/g ] };\n";
                    foreach (array("bac", "bra", "sqlite_quo", "mssql_bra") as $X) echo "jushLinks.$X = jushLinks.$y;\n";
                }
                $lh = $g->server_info;
                echo 'bodyLoad(\'', (is_object($g) ? preg_replace('~^(\d\.?\d).*~s', '\1', $lh) : ""), '\'', (preg_match('~MariaDB~', $lh) ? ", true" : ""), ');
</script>
';
            }
            $this->databasesPrint($Te);
            if (DB == "" || !$Te) {
                echo "<p class='links'>" . (support("sql") ? "<a href='" . h(ME) . "sql='" . bold(isset($_GET["sql"]) && !isset($_GET["import"])) . ">" . 'SQL command' . "</a>\n<a href='" . h(ME) . "import='" . bold(isset($_GET["import"])) . ">" . 'Import' . "</a>\n" : "") . "";
                if (support("dump")) echo "<a href='" . h(ME) . "dump=" . urlencode(isset($_GET["table"]) ? $_GET["table"] : $_GET["select"]) . "' id='dump'" . bold(isset($_GET["dump"])) . ">" . 'Export' . "</a>\n";
            }
            if ($_GET["ns"] !== "" && !$Te && DB != "") {
                echo '<a href="' . h(ME) . 'create="' . bold($_GET["create"] === "") . ">" . 'Create table' . "</a>\n";
                if (!$S) echo "<p class='message'>" . 'No tables.' . "\n"; else$this->tablesPrint($S);
            }
        }
    }

    function
    name()
    {
        return "<a href='https://www.adminer.org/'" . target_blank() . " id='h1'>Adminer</a>";
    }

    function
    serverName($N)
    {
        return
            h($N);
    }

    function
    databasesPrint($Te)
    {
        global $b, $g;
        $l = $this->databases();
        if ($l && !in_array(DB, $l)) array_unshift($l, DB);
        echo '<form action="">
<p id="dbs">
';
        hidden_fields_get();
        $Ob = script("mixin(qsl('select'), {onmousedown: dbMouseDown, onchange: dbChange});");
        echo "<span title='" . 'database' . "'>" . 'DB' . "</span>: " . ($l ? "<select name='db'>" . optionlist(array("" => "") + $l, DB) . "</select>$Ob" : "<input name='db' value='" . h(DB) . "' autocapitalize='off'>\n"), "<input type='submit' value='" . 'Use' . "'" . ($l ? " class='hidden'" : "") . ">\n";
        if ($Te != "db" && DB != "" && $g->select_db(DB)) {
            if (support("scheme")) {
                echo "<br>" . 'Schema' . ": <select name='ns'>" . optionlist(array("" => "") + $b->schemas(), $_GET["ns"]) . "</select>$Ob";
                if ($_GET["ns"] != "") set_schema($_GET["ns"]);
            }
        }
        foreach (array("import", "sql", "schema", "dump", "privileges") as $X) {
            if (isset($_GET[$X])) {
                echo "<input type='hidden' name='$X' value=''>";
                break;
            }
        }
        echo "</p></form>\n";
    }

    function
    databases($ad = true)
    {
        return
            get_databases($ad);
    }

    function
    tablesPrint($S)
    {
        echo "<ul id='tables'>" . script("mixin(qs('#tables'), {onmouseover: menuOver, onmouseout: menuOut});");
        foreach ($S
                 as $Q => $Fh) {
            $C = $this->tableName($Fh);
            if ($C != "") {
                echo '<li><a href="' . h(ME) . 'select=' . urlencode($Q) . '"' . bold($_GET["select"] == $Q || $_GET["edit"] == $Q, "select") . ">" . 'select' . "</a> ", (support("table") || support("indexes") ? '<a href="' . h(ME) . 'table=' . urlencode($Q) . '"' . bold(in_array($Q, array($_GET["table"], $_GET["create"], $_GET["indexes"], $_GET["foreign"], $_GET["trigger"])), (is_view($Fh) ? "view" : "structure")) . " title='" . 'Show structure' . "'>$C</a>" : "<span>$C</span>") . "\n";
            }
        }
        echo "</ul>\n";
    }

    function
    tableName($Ph)
    {
        return
            h($Ph["Name"]);
    }
}

$b = (function_exists('adminer_object') ? adminer_object() : new
Adminer);
if ($b->operators === null) $b->operators = $vf;
function
page_header($ii, $o = "", $Xa = array(), $ji = "")
{
    global $ca, $ia, $b, $ec, $y;
    page_headers();
    if (is_ajax() && $o) {
        page_messages($o);
        exit;
    }
    $ki = $ii . ($ji != "" ? ": $ji" : "");
    $li = strip_tags($ki . (SERVER != "" && SERVER != "localhost" ? h(" - " . SERVER) : "") . " - " . $b->name());
    echo '<!DOCTYPE html>
<html lang="en" dir="ltr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex">
<title>', $li, '</title>
<link rel="stylesheet" type="text/css" href="', h(preg_replace("~\\?.*~", "", ME) . "?file=default.css&version=4.7.3"), '">
', script_src(preg_replace("~\\?.*~", "", ME) . "?file=functions.js&version=4.7.3");
    if ($b->head()) {
        echo '<link rel="shortcut icon" type="image/x-icon" href="', h(preg_replace("~\\?.*~", "", ME) . "?file=favicon.ico&version=4.7.3"), '">
<link rel="apple-touch-icon" href="', h(preg_replace("~\\?.*~", "", ME) . "?file=favicon.ico&version=4.7.3"), '">
';
        foreach ($b->css() as $Ib) {
            echo '<link rel="stylesheet" type="text/css" href="', h($Ib), '">
';
        }
    }
    echo '
<body class="ltr nojs">
';
    $Uc = get_temp_dir() . "/adminer.version";
    if (!$_COOKIE["adminer_version"] && function_exists('openssl_verify') && file_exists($Uc) && filemtime($Uc) + 86400 > time()) {
        $Zi = unserialize(file_get_contents($Uc));
        $tg = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwqWOVuF5uw7/+Z70djoK
RlHIZFZPO0uYRezq90+7Amk+FDNd7KkL5eDve+vHRJBLAszF/7XKXe11xwliIsFs
DFWQlsABVZB3oisKCBEuI71J4kPH8dKGEWR9jDHFw3cWmoH3PmqImX6FISWbG3B8
h7FIx3jEaw5ckVPVTeo5JRm/1DZzJxjyDenXvBQ/6o9DgZKeNDgxwKzH+sw9/YCO
jHnq1cFpOIISzARlrHMa/43YfeNRAm/tsBXjSxembBPo7aQZLAWHmaj5+K19H10B
nCpz9Y++cipkVEiKRGih4ZEvjoFysEOdRLj6WiD/uUNky4xGeA6LaJqh5XpkFkcQ
fQIDAQAB
-----END PUBLIC KEY-----
";
        if (openssl_verify($Zi["version"], base64_decode($Zi["signature"]), $tg) == 1) $_COOKIE["adminer_version"] = $Zi["version"];
    }
    echo '<script', nonce(), '>
mixin(document.body, {onkeydown: bodyKeydown, onclick: bodyClick', (isset($_COOKIE["adminer_version"]) ? "" : ", onload: partial(verifyVersion, '$ia', '" . js_escape(ME) . "', '" . get_token() . "')"); ?>});
    document.body.className = document.body.className.replace(/ nojs/, ' js');
    var offlineMessage = '<?php echo
js_escape('You are offline.'), '\';
var thousandsSeparator = \'', js_escape(','), '\';
</script>

<div id="help" class="jush-', $y, ' jsonly hidden"></div>
', script("mixin(qs('#help'), {onmouseover: function () { helpOpen = 1; }, onmouseout: helpMouseout});"), '
<div id="content">
';
    if ($Xa !== null) {
        $A = substr(preg_replace('~\b(username|db|ns)=[^&]*&~', '', ME), 0, -1);
        echo '<p id="breadcrumb"><a href="' . h($A ? $A : ".") . '">' . $ec[DRIVER] . '</a> &raquo; ';
        $A = substr(preg_replace('~\b(db|ns)=[^&]*&~', '', ME), 0, -1);
        $N = $b->serverName(SERVER);
        $N = ($N != "" ? $N : 'Server');
        if ($Xa === false) echo "$N\n"; else {
            echo "<a href='" . ($A ? h($A) : ".") . "' accesskey='1' title='Alt+Shift+1'>$N</a> &raquo; ";
            if ($_GET["ns"] != "" || (DB != "" && is_array($Xa))) echo '<a href="' . h($A . "&db=" . urlencode(DB) . (support("scheme") ? "&ns=" : "")) . '">' . h(DB) . '</a> &raquo; ';
            if (is_array($Xa)) {
                if ($_GET["ns"] != "") echo '<a href="' . h(substr(ME, 0, -1)) . '">' . h($_GET["ns"]) . '</a> &raquo; ';
                foreach ($Xa
                         as $z => $X) {
                    $Wb = (is_array($X) ? $X[1] : h($X));
                    if ($Wb != "") echo "<a href='" . h(ME . "$z=") . urlencode(is_array($X) ? $X[0] : $X) . "'>$Wb</a> &raquo; ";
                }
            }
            echo "$ii\n";
        }
    }
    echo "<h2>$ki</h2>\n", "<div id='ajaxstatus' class='jsonly hidden'></div>\n";
    restart_session();
    page_messages($o);
    $l =& get_session("dbs");
    if (DB != "" && $l && !in_array(DB, $l, true)) $l = null;
    stop_session();
    define("PAGE_HEADER", 1);
}

function
page_headers()
{
    global $b;
    header("Content-Type: text/html; charset=utf-8");
    header("Cache-Control: no-cache");
    header("X-Frame-Options: deny");
    header("X-XSS-Protection: 0");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: origin-when-cross-origin");
    foreach ($b->csp() as $Hb) {
        $wd = array();
        foreach ($Hb
                 as $z => $X) $wd[] = "$z $X";
        header("Content-Security-Policy: " . implode("; ", $wd));
    }
    $b->headers();
}

function
csp()
{
    return
        array(array("script-src" => "'self' 'unsafe-inline' 'nonce-" . get_nonce() . "' 'strict-dynamic'", "connect-src" => "'self'", "frame-src" => "https://www.adminer.org", "object-src" => "'none'", "base-uri" => "'none'", "form-action" => "'self'",),);
}

function
get_nonce()
{
    static $df;
    if (!$df) $df = base64_encode(rand_string());
    return $df;
}

function
page_messages($o)
{
    $Li = preg_replace('~^[^?]*~', '', $_SERVER["REQUEST_URI"]);
    $Pe = $_SESSION["messages"][$Li];
    if ($Pe) {
        echo "<div class='message'>" . implode("</div>\n<div class='message'>", $Pe) . "</div>" . script("messagesPrint();");
        unset($_SESSION["messages"][$Li]);
    }
    if ($o) echo "<div class='error'>$o</div>\n";
}

function
page_footer($Te = "")
{
    global $b, $pi;
    echo '</div>

';
    if ($Te != "auth") {
        echo '<form action="" method="post">
<p class="logout">
<input type="submit" name="logout" value="Logout" id="logout">
<input type="hidden" name="token" value="', $pi, '">
</p>
</form>
';
    }
    echo '<div id="menu">
';
    $b->navigation($Te);
    echo '</div>
', script("setupSubmitHighlight(document);");
}

function
int32($We)
{
    while ($We >= 2147483648) $We -= 4294967296;
    while ($We <= -2147483649) $We += 4294967296;
    return (int)$We;
}

function
long2str($W, $dj)
{
    $Yg = '';
    foreach ($W
             as $X) $Yg .= pack('V', $X);
    if ($dj) return
        substr($Yg, 0, end($W));
    return $Yg;
}

function
str2long($Yg, $dj)
{
    $W = array_values(unpack('V*', str_pad($Yg, 4 * ceil(strlen($Yg) / 4), "\0")));
    if ($dj) $W[] = strlen($Yg);
    return $W;
}

function
xxtea_mx($qj, $pj, $Mh, $de)
{
    return
        int32((($qj >> 5 & 0x7FFFFFF) ^ $pj << 2) + (($pj >> 3 & 0x1FFFFFFF) ^ $qj << 4)) ^ int32(($Mh ^ $pj) + ($de ^ $qj));
}

function
encrypt_string($Hh, $z)
{
    if ($Hh == "") return "";
    $z = array_values(unpack("V*", pack("H*", md5($z))));
    $W = str2long($Hh, true);
    $We = count($W) - 1;
    $qj = $W[$We];
    $pj = $W[0];
    $ug = floor(6 + 52 / ($We + 1));
    $Mh = 0;
    while ($ug-- > 0) {
        $Mh = int32($Mh + 0x9E3779B9);
        $lc = $Mh >> 2 & 3;
        for ($Lf = 0; $Lf < $We; $Lf++) {
            $pj = $W[$Lf + 1];
            $Ve = xxtea_mx($qj, $pj, $Mh, $z[$Lf & 3 ^ $lc]);
            $qj = int32($W[$Lf] + $Ve);
            $W[$Lf] = $qj;
        }
        $pj = $W[0];
        $Ve = xxtea_mx($qj, $pj, $Mh, $z[$Lf & 3 ^ $lc]);
        $qj = int32($W[$We] + $Ve);
        $W[$We] = $qj;
    }
    return
        long2str($W, false);
}

function
decrypt_string($Hh, $z)
{
    if ($Hh == "") return "";
    if (!$z) return
        false;
    $z = array_values(unpack("V*", pack("H*", md5($z))));
    $W = str2long($Hh, false);
    $We = count($W) - 1;
    $qj = $W[$We];
    $pj = $W[0];
    $ug = floor(6 + 52 / ($We + 1));
    $Mh = int32($ug * 0x9E3779B9);
    while ($Mh) {
        $lc = $Mh >> 2 & 3;
        for ($Lf = $We; $Lf > 0; $Lf--) {
            $qj = $W[$Lf - 1];
            $Ve = xxtea_mx($qj, $pj, $Mh, $z[$Lf & 3 ^ $lc]);
            $pj = int32($W[$Lf] - $Ve);
            $W[$Lf] = $pj;
        }
        $qj = $W[$We];
        $Ve = xxtea_mx($qj, $pj, $Mh, $z[$Lf & 3 ^ $lc]);
        $pj = int32($W[0] - $Ve);
        $W[0] = $pj;
        $Mh = int32($Mh - 0x9E3779B9);
    }
    return
        long2str($W, true);
}

$g = '';
$vd = $_SESSION["token"];
if (!$vd) $_SESSION["token"] = rand(1, 1e6);
$pi = get_token();
$bg = array();
if ($_COOKIE["adminer_permanent"]) {
    foreach (explode(" ", $_COOKIE["adminer_permanent"]) as $X) {
        list($z) = explode(":", $X);
        $bg[$z] = $X;
    }
}
function
add_invalid_login()
{
    global $b;
    $id = file_open_lock(get_temp_dir() . "/adminer.invalid");
    if (!$id) return;
    $Wd = unserialize(stream_get_contents($id));
    $fi = time();
    if ($Wd) {
        foreach ($Wd
                 as $Xd => $X) {
            if ($X[0] < $fi) unset($Wd[$Xd]);
        }
    }
    $Vd =& $Wd[$b->bruteForceKey()];
    if (!$Vd) $Vd = array($fi + 30 * 60, 0);
    $Vd[1]++;
    file_write_unlock($id, serialize($Wd));
}

function
check_invalid_login()
{
    global $b;
    $Wd = unserialize(@file_get_contents(get_temp_dir() . "/adminer.invalid"));
    $Vd = $Wd[$b->bruteForceKey()];
    $cf = ($Vd[1] > 29 ? $Vd[0] - time() : 0);
    if ($cf > 0) auth_error(lang(array('Too many unsuccessful logins, try again in %d minute.', 'Too many unsuccessful logins, try again in %d minutes.'), ceil($cf / 60)));
}

$Ka = $_POST["auth"];
if ($Ka) {
    session_regenerate_id();
    $Yi = $Ka["driver"];
    $N = $Ka["server"];
    $V = $Ka["username"];
    $F = (string)$Ka["password"];
    $m = $Ka["db"];
    set_password($Yi, $N, $V, $F);
    $_SESSION["db"][$Yi][$N][$V][$m] = true;
    if ($Ka["permanent"]) {
        $z = base64_encode($Yi) . "-" . base64_encode($N) . "-" . base64_encode($V) . "-" . base64_encode($m);
        $ng = $b->permanentLogin(true);
        $bg[$z] = "$z:" . base64_encode($ng ? encrypt_string($F, $ng) : "");
        cookie("adminer_permanent", implode(" ", $bg));
    }
    if (count($_POST) == 1 || DRIVER != $Yi || SERVER != $N || $_GET["username"] !== $V || DB != $m) redirect(auth_url($Yi, $N, $V, $m));
} elseif ($_POST["logout"]) {
    if ($vd && !verify_token()) {
        page_header('Logout', 'Invalid CSRF token. Send the form again.');
        page_footer("db");
        exit;
    } else {
        foreach (array("pwds", "db", "dbs", "queries") as $z) set_session($z, null);
        unset_permanent();
        redirect(substr(preg_replace('~\b(username|db|ns)=[^&]*&~', '', ME), 0, -1), 'Logout successful.' . ' ' . 'Thanks for using Adminer, consider <a href="https://www.adminer.org/en/donation/">donating</a>.');
    }
} elseif ($bg && !$_SESSION["pwds"]) {
    session_regenerate_id();
    $ng = $b->permanentLogin();
    foreach ($bg
             as $z => $X) {
        list(, $jb) = explode(":", $X);
        list($Yi, $N, $V, $m) = array_map('base64_decode', explode("-", $z));
        set_password($Yi, $N, $V, decrypt_string(base64_decode($jb), $ng));
        $_SESSION["db"][$Yi][$N][$V][$m] = true;
    }
}
function
unset_permanent()
{
    global $bg;
    foreach ($bg
             as $z => $X) {
        list($Yi, $N, $V, $m) = array_map('base64_decode', explode("-", $z));
        if ($Yi == DRIVER && $N == SERVER && $V == $_GET["username"] && $m == DB) unset($bg[$z]);
    }
    cookie("adminer_permanent", implode(" ", $bg));
}

function
auth_error($o)
{
    global $b, $vd;
    $nh = session_name();
    if (isset($_GET["username"])) {
        header("HTTP/1.1 403 Forbidden");
        if (($_COOKIE[$nh] || $_GET[$nh]) && !$vd) $o = 'Session expired, please login again.'; else {
            restart_session();
            add_invalid_login();
            $F = get_password();
            if ($F !== null) {
                if ($F === false) $o .= '<br>' . sprintf('Master password expired. <a href="https://www.adminer.org/en/extension/"%s>Implement</a> %s method to make it permanent.', target_blank(), '<code>permanentLogin()</code>');
                set_password(DRIVER, SERVER, $_GET["username"], null);
            }
            unset_permanent();
        }
    }
    if (!$_COOKIE[$nh] && $_GET[$nh] && ini_bool("session.use_only_cookies")) $o = 'Session support must be enabled.';
    $Of = session_get_cookie_params();
    cookie("adminer_key", ($_COOKIE["adminer_key"] ? $_COOKIE["adminer_key"] : rand_string()), $Of["lifetime"]);
    page_header('Login', $o, null);
    echo "<form action='' method='post'>\n", "<div>";
    if (hidden_fields($_POST, array("auth"))) echo "<p class='message'>" . 'The action will be performed after successful login with the same credentials.' . "\n";
    echo "</div>\n";
    $b->loginForm();
    echo "</form>\n";
    page_footer("auth");
    exit;
}

if (isset($_GET["username"]) && !class_exists("Min_DB")) {
    unset($_SESSION["pwds"][DRIVER]);
    unset_permanent();
    page_header('No extension', sprintf('None of the supported PHP extensions (%s) are available.', implode(", ", $hg)), false);
    page_footer("auth");
    exit;
}
stop_session(true);
if (isset($_GET["username"]) && is_string(get_password())) {
    list($Ad, $dg) = explode(":", SERVER, 2);
    if (is_numeric($dg) && $dg < 1024) auth_error('Connecting to privileged ports is not allowed.');
    check_invalid_login();
    $g = connect();
    $n = new
    Min_Driver($g);
}
$ye = null;
if (!is_object($g) || ($ye = $b->login($_GET["username"], get_password())) !== true) {
    $o = (is_string($g) ? h($g) : (is_string($ye) ? $ye : 'Invalid credentials.'));
    auth_error($o . (preg_match('~^ | $~', get_password()) ? '<br>' . 'There is a space in the input password which might be the cause.' : ''));
}
if ($Ka && $_POST["token"]) $_POST["token"] = $pi;
$o = '';
if ($_POST) {
    if (!verify_token()) {
        $Qd = "max_input_vars";
        $Je = ini_get($Qd);
        if (extension_loaded("suhosin")) {
            foreach (array("suhosin.request.max_vars", "suhosin.post.max_vars") as $z) {
                $X = ini_get($z);
                if ($X && (!$Je || $X < $Je)) {
                    $Qd = $z;
                    $Je = $X;
                }
            }
        }
        $o = (!$_POST["token"] && $Je ? sprintf('Maximum number of allowed fields exceeded. Please increase %s.', "'$Qd'") : 'Invalid CSRF token. Send the form again.' . ' ' . 'If you did not send this request from Adminer then close this page.');
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $o = sprintf('Too big POST data. Reduce the data or increase the %s configuration directive.', "'post_max_size'");
    if (isset($_GET["sql"])) $o .= ' ' . 'You can upload a big SQL file via FTP and import it from server.';
}
function
select($H, $h = null, $Cf = array(), $_ = 0)
{
    global $y;
    $we = array();
    $x = array();
    $f = array();
    $Ua = array();
    $U = array();
    $I = array();
    odd('');
    for ($t = 0; (!$_ || $t < $_) && ($J = $H->fetch_row()); $t++) {
        if (!$t) {
            echo "<div class='scrollable'>\n", "<table cellspacing='0' class='nowrap'>\n", "<thead><tr>";
            for ($ce = 0; $ce < count($J); $ce++) {
                $p = $H->fetch_field();
                $C = $p->name;
                $Bf = $p->orgtable;
                $Af = $p->orgname;
                $I[$p->table] = $Bf;
                if ($Cf && $y == "sql") $we[$ce] = ($C == "table" ? "table=" : ($C == "possible_keys" ? "indexes=" : null)); elseif ($Bf != "") {
                    if (!isset($x[$Bf])) {
                        $x[$Bf] = array();
                        foreach (indexes($Bf, $h) as $w) {
                            if ($w["type"] == "PRIMARY") {
                                $x[$Bf] = array_flip($w["columns"]);
                                break;
                            }
                        }
                        $f[$Bf] = $x[$Bf];
                    }
                    if (isset($f[$Bf][$Af])) {
                        unset($f[$Bf][$Af]);
                        $x[$Bf][$Af] = $ce;
                        $we[$ce] = $Bf;
                    }
                }
                if ($p->charsetnr == 63) $Ua[$ce] = true;
                $U[$ce] = $p->type;
                echo "<th" . ($Bf != "" || $p->name != $Af ? " title='" . h(($Bf != "" ? "$Bf." : "") . $Af) . "'" : "") . ">" . h($C) . ($Cf ? doc_link(array('sql' => "explain-output.html#explain_" . strtolower($C), 'mariadb' => "explain/#the-columns-in-explain-select",)) : "");
            }
            echo "</thead>\n";
        }
        echo "<tr" . odd() . ">";
        foreach ($J
                 as $z => $X) {
            if ($X === null) $X = "<i>NULL</i>"; elseif ($Ua[$z] && !is_utf8($X)) $X = "<i>" . lang(array('%d byte', '%d bytes'), strlen($X)) . "</i>";
            else {
                $X = h($X);
                if ($U[$z] == 254) $X = "<code>$X</code>";
            }
            if (isset($we[$z]) && !$f[$we[$z]]) {
                if ($Cf && $y == "sql") {
                    $Q = $J[array_search("table=", $we)];
                    $A = $we[$z] . urlencode($Cf[$Q] != "" ? $Cf[$Q] : $Q);
                } else {
                    $A = "edit=" . urlencode($we[$z]);
                    foreach ($x[$we[$z]] as $nb => $ce) $A .= "&where" . urlencode("[" . bracket_escape($nb) . "]") . "=" . urlencode($J[$ce]);
                }
                $X = "<a href='" . h(ME . $A) . "'>$X</a>";
            }
            echo "<td>$X";
        }
    }
    echo ($t ? "</table>\n</div>" : "<p class='message'>" . 'No rows.') . "\n";
    return $I;
}

function
referencable_primary($hh)
{
    $I = array();
    foreach (table_status('', true) as $Qh => $Q) {
        if ($Qh != $hh && fk_support($Q)) {
            foreach (fields($Qh) as $p) {
                if ($p["primary"]) {
                    if ($I[$Qh]) {
                        unset($I[$Qh]);
                        break;
                    }
                    $I[$Qh] = $p;
                }
            }
        }
    }
    return $I;
}

function
adminer_settings()
{
    parse_str($_COOKIE["adminer_settings"], $ph);
    return $ph;
}

function
adminer_setting($z)
{
    $ph = adminer_settings();
    return $ph[$z];
}

function
set_adminer_settings($ph)
{
    return
        cookie("adminer_settings", http_build_query($ph + adminer_settings()));
}

function
textarea($C, $Y, $K = 10, $rb = 80)
{
    global $y;
    echo "<textarea name='$C' rows='$K' cols='$rb' class='sqlarea jush-$y' spellcheck='false' wrap='off'>";
    if (is_array($Y)) {
        foreach ($Y
                 as $X) echo
            h($X[0]) . "\n\n\n";
    } else
        echo
        h($Y);
    echo "</textarea>";
}

function
edit_type($z, $p, $pb, $ed = array(), $Lc = array())
{
    global $Ih, $U, $Ji, $qf;
    $T = $p["type"];
    echo '<td><select name="', h($z), '[type]" class="type" aria-labelledby="label-type">';
    if ($T && !isset($U[$T]) && !isset($ed[$T]) && !in_array($T, $Lc)) $Lc[] = $T;
    if ($ed) $Ih['Foreign keys'] = $ed;
    echo
    optionlist(array_merge($Lc, $Ih), $T), '</select>
', on_help("getTarget(event).value", 1), script("mixin(qsl('select'), {onfocus: function () { lastType = selectValue(this); }, onchange: editingTypeChange});", ""), '<td><input name="', h($z), '[length]" value="', h($p["length"]), '" size="3"', (!$p["length"] && preg_match('~var(char|binary)$~', $T) ? " class='required'" : "");
    echo ' aria-labelledby="label-length">', script("mixin(qsl('input'), {onfocus: editingLengthFocus, oninput: editingLengthChange});", ""), '<td class="options">', "<select name='" . h($z) . "[collation]'" . (preg_match('~(char|text|enum|set)$~', $T) ? "" : " class='hidden'") . '><option value="">(' . 'collation' . ')' . optionlist($pb, $p["collation"]) . '</select>', ($Ji ? "<select name='" . h($z) . "[unsigned]'" . (!$T || preg_match(number_type(), $T) ? "" : " class='hidden'") . '><option>' . optionlist($Ji, $p["unsigned"]) . '</select>' : ''), (isset($p['on_update']) ? "<select name='" . h($z) . "[on_update]'" . (preg_match('~timestamp|datetime~', $T) ? "" : " class='hidden'") . '>' . optionlist(array("" => "(" . 'ON UPDATE' . ")", "CURRENT_TIMESTAMP"), (preg_match('~^CURRENT_TIMESTAMP~i', $p["on_update"]) ? "CURRENT_TIMESTAMP" : $p["on_update"])) . '</select>' : ''), ($ed ? "<select name='" . h($z) . "[on_delete]'" . (preg_match("~`~", $T) ? "" : " class='hidden'") . "><option value=''>(" . 'ON DELETE' . ")" . optionlist(explode("|", $qf), $p["on_delete"]) . "</select> " : " ");
}

function
process_length($te)
{
    global $wc;
    return (preg_match("~^\\s*\\(?\\s*$wc(?:\\s*,\\s*$wc)*+\\s*\\)?\\s*\$~", $te) && preg_match_all("~$wc~", $te, $De) ? "(" . implode(",", $De[0]) . ")" : preg_replace('~^[0-9].*~', '(\0)', preg_replace('~[^-0-9,+()[\]]~', '', $te)));
}

function
process_type($p, $ob = "COLLATE")
{
    global $Ji;
    return " $p[type]" . process_length($p["length"]) . (preg_match(number_type(), $p["type"]) && in_array($p["unsigned"], $Ji) ? " $p[unsigned]" : "") . (preg_match('~char|text|enum|set~', $p["type"]) && $p["collation"] ? " $ob " . q($p["collation"]) : "");
}

function
process_field($p, $Bi)
{
    return
        array(idf_escape(trim($p["field"])), process_type($Bi), ($p["null"] ? " NULL" : " NOT NULL"), default_value($p), (preg_match('~timestamp|datetime~', $p["type"]) && $p["on_update"] ? " ON UPDATE $p[on_update]" : ""), (support("comment") && $p["comment"] != "" ? " COMMENT " . q($p["comment"]) : ""), ($p["auto_increment"] ? auto_increment() : null),);
}

function
default_value($p)
{
    $Sb = $p["default"];
    return ($Sb === null ? "" : " DEFAULT " . (preg_match('~char|binary|text|enum|set~', $p["type"]) || preg_match('~^(?![a-z])~i', $Sb) ? q($Sb) : $Sb));
}

function
type_class($T)
{
    foreach (array('char' => 'text', 'date' => 'time|year', 'binary' => 'blob', 'enum' => 'set',) as $z => $X) {
        if (preg_match("~$z|$X~", $T)) return " class='$z'";
    }
}

function
edit_fields($q, $pb, $T = "TABLE", $ed = array())
{
    global $Rd;
    $q = array_values($q);
    echo '<thead><tr>
';
    if ($T == "PROCEDURE") {
        echo '<td>';
    }
    echo '<th id="label-name">', ($T == "TABLE" ? 'Column name' : 'Parameter name'), '<td id="label-type">Type<textarea id="enum-edit" rows="4" cols="12" wrap="off" style="display: none;"></textarea>', script("qs('#enum-edit').onblur = editingLengthBlur;"), '<td id="label-length">Length
<td>', 'Options';
    if ($T == "TABLE") {
        echo '<td id="label-null">NULL
<td><input type="radio" name="auto_increment_col" value=""><acronym id="label-ai" title="Auto Increment">AI</acronym>', doc_link(array('sql' => "example-auto-increment.html", 'mariadb' => "auto_increment/", 'sqlite' => "autoinc.html", 'pgsql' => "datatype.html#DATATYPE-SERIAL", 'mssql' => "ms186775.aspx",)), '<td id="label-default">Default value
', (support("comment") ? "<td id='label-comment'>" . 'Comment' : "");
    }
    echo '<td>', "<input type='image' class='icon' name='add[" . (support("move_col") ? 0 : count($q)) . "]' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=plus.gif&version=4.7.3") . "' alt='+' title='" . 'Add next' . "'>" . script("row_count = " . count($q) . ";"), '</thead>
<tbody>
', script("mixin(qsl('tbody'), {onclick: editingClick, onkeydown: editingKeydown, oninput: editingInput});");
    foreach ($q
             as $t => $p) {
        $t++;
        $Df = $p[($_POST ? "orig" : "field")];
        $ac = (isset($_POST["add"][$t - 1]) || (isset($p["field"]) && !$_POST["drop_col"][$t])) && (support("drop_col") || $Df == "");
        echo '<tr', ($ac ? "" : " style='display: none;'"), '>
', ($T == "PROCEDURE" ? "<td>" . html_select("fields[$t][inout]", explode("|", $Rd), $p["inout"]) : ""), '<th>';
        if ($ac) {
            echo '<input name="fields[', $t, '][field]" value="', h($p["field"]), '" data-maxlength="64" autocapitalize="off" aria-labelledby="label-name">', script("qsl('input').oninput = function () { editingNameChange.call(this);" . ($p["field"] != "" || count($q) > 1 ? "" : " editingAddRow.call(this);") . " };", "");
        }
        echo '<input type="hidden" name="fields[', $t, '][orig]" value="', h($Df), '">
';
        edit_type("fields[$t]", $p, $pb, $ed);
        if ($T == "TABLE") {
            echo '<td>', checkbox("fields[$t][null]", 1, $p["null"], "", "", "block", "label-null"), '<td><label class="block"><input type="radio" name="auto_increment_col" value="', $t, '"';
            if ($p["auto_increment"]) {
                echo ' checked';
            }
            echo ' aria-labelledby="label-ai"></label><td>', checkbox("fields[$t][has_default]", 1, $p["has_default"], "", "", "", "label-default"), '<input name="fields[', $t, '][default]" value="', h($p["default"]), '" aria-labelledby="label-default">', (support("comment") ? "<td><input name='fields[$t][comment]' value='" . h($p["comment"]) . "' data-maxlength='" . (min_version(5.5) ? 1024 : 255) . "' aria-labelledby='label-comment'>" : "");
        }
        echo "<td>", (support("move_col") ? "<input type='image' class='icon' name='add[$t]' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=plus.gif&version=4.7.3") . "' alt='+' title='" . 'Add next' . "'> " . "<input type='image' class='icon' name='up[$t]' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=up.gif&version=4.7.3") . "' alt='↑' title='" . 'Move up' . "'> " . "<input type='image' class='icon' name='down[$t]' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=down.gif&version=4.7.3") . "' alt='↓' title='" . 'Move down' . "'> " : ""), ($Df == "" || support("drop_col") ? "<input type='image' class='icon' name='drop_col[$t]' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=cross.gif&version=4.7.3") . "' alt='x' title='" . 'Remove' . "'>" : "");
    }
}

function
process_fields(&$q)
{
    $D = 0;
    if ($_POST["up"]) {
        $ne = 0;
        foreach ($q
                 as $z => $p) {
            if (key($_POST["up"]) == $z) {
                unset($q[$z]);
                array_splice($q, $ne, 0, array($p));
                break;
            }
            if (isset($p["field"])) $ne = $D;
            $D++;
        }
    } elseif ($_POST["down"]) {
        $gd = false;
        foreach ($q
                 as $z => $p) {
            if (isset($p["field"]) && $gd) {
                unset($q[key($_POST["down"])]);
                array_splice($q, $D, 0, array($gd));
                break;
            }
            if (key($_POST["down"]) == $z) $gd = $p;
            $D++;
        }
    } elseif ($_POST["add"]) {
        $q = array_values($q);
        array_splice($q, key($_POST["add"]), 0, array(array()));
    } elseif (!$_POST["drop_col"]) return
        false;
    return
        true;
}

function
normalize_enum($B)
{
    return "'" . str_replace("'", "''", addcslashes(stripcslashes(str_replace($B[0][0] . $B[0][0], $B[0][0], substr($B[0], 1, -1))), '\\')) . "'";
}

function
grant($ld, $pg, $f, $pf)
{
    if (!$pg) return
        true;
    if ($pg == array("ALL PRIVILEGES", "GRANT OPTION")) return ($ld == "GRANT" ? queries("$ld ALL PRIVILEGES$pf WITH GRANT OPTION") : queries("$ld ALL PRIVILEGES$pf") && queries("$ld GRANT OPTION$pf"));
    return
        queries("$ld " . preg_replace('~(GRANT OPTION)\([^)]*\)~', '\1', implode("$f, ", $pg) . $f) . $pf);
}

function
drop_create($fc, $i, $gc, $ci, $ic, $xe, $Oe, $Me, $Ne, $mf, $Ze)
{
    if ($_POST["drop"]) query_redirect($fc, $xe, $Oe); elseif ($mf == "") query_redirect($i, $xe, $Ne);
    elseif ($mf != $Ze) {
        $Gb = queries($i);
        queries_redirect($xe, $Me, $Gb && queries($fc));
        if ($Gb) queries($gc);
    } else
        queries_redirect($xe, $Me, queries($ci) && queries($ic) && queries($fc) && queries($i));
}

function
create_trigger($pf, $J)
{
    global $y;
    $hi = " $J[Timing] $J[Event]" . ($J["Event"] == "UPDATE OF" ? " " . idf_escape($J["Of"]) : "");
    return "CREATE TRIGGER " . idf_escape($J["Trigger"]) . ($y == "mssql" ? $pf . $hi : $hi . $pf) . rtrim(" $J[Type]\n$J[Statement]", ";") . ";";
}

function
create_routine($Ug, $J)
{
    global $Rd, $y;
    $O = array();
    $q = (array)$J["fields"];
    ksort($q);
    foreach ($q
             as $p) {
        if ($p["field"] != "") $O[] = (preg_match("~^($Rd)\$~", $p["inout"]) ? "$p[inout] " : "") . idf_escape($p["field"]) . process_type($p, "CHARACTER SET");
    }
    $Tb = rtrim("\n$J[definition]", ";");
    return "CREATE $Ug " . idf_escape(trim($J["name"])) . " (" . implode(", ", $O) . ")" . (isset($_GET["function"]) ? " RETURNS" . process_type($J["returns"], "CHARACTER SET") : "") . ($J["language"] ? " LANGUAGE $J[language]" : "") . ($y == "pgsql" ? " AS " . q($Tb) : "$Tb;");
}

function
remove_definer($G)
{
    return
        preg_replace('~^([A-Z =]+) DEFINER=`' . preg_replace('~@(.*)~', '`@`(%|\1)', logged_user()) . '`~', '\1', $G);
}

function
format_foreign_key($r)
{
    global $qf;
    $m = $r["db"];
    $ef = $r["ns"];
    return " FOREIGN KEY (" . implode(", ", array_map('idf_escape', $r["source"])) . ") REFERENCES " . ($m != "" && $m != $_GET["db"] ? idf_escape($m) . "." : "") . ($ef != "" && $ef != $_GET["ns"] ? idf_escape($ef) . "." : "") . table($r["table"]) . " (" . implode(", ", array_map('idf_escape', $r["target"])) . ")" . (preg_match("~^($qf)\$~", $r["on_delete"]) ? " ON DELETE $r[on_delete]" : "") . (preg_match("~^($qf)\$~", $r["on_update"]) ? " ON UPDATE $r[on_update]" : "");
}

function
tar_file($Uc, $mi)
{
    $I = pack("a100a8a8a8a12a12", $Uc, 644, 0, 0, decoct($mi->size), decoct(time()));
    $hb = 8 * 32;
    for ($t = 0; $t < strlen($I); $t++) $hb += ord($I[$t]);
    $I .= sprintf("%06o", $hb) . "\0 ";
    echo $I, str_repeat("\0", 512 - strlen($I));
    $mi->send();
    echo
    str_repeat("\0", 511 - ($mi->size + 511) % 512);
}

function
ini_bytes($Qd)
{
    $X = ini_get($Qd);
    switch (strtolower(substr($X, -1))) {
        case'g':
            $X *= 1024;
        case'm':
            $X *= 1024;
        case'k':
            $X *= 1024;
    }
    return $X;
}

function
doc_link($Zf, $di = "<sup>?</sup>")
{
    global $y, $g;
    $lh = $g->server_info;
    $Zi = preg_replace('~^(\d\.?\d).*~s', '\1', $lh);
    $Oi = array('sql' => "https://dev.mysql.com/doc/refman/$Zi/en/", 'sqlite' => "https://www.sqlite.org/", 'pgsql' => "https://www.postgresql.org/docs/$Zi/static/", 'mssql' => "https://msdn.microsoft.com/library/", 'oracle' => "https://download.oracle.com/docs/cd/B19306_01/server.102/b14200/",);
    if (preg_match('~MariaDB~', $lh)) {
        $Oi['sql'] = "https://mariadb.com/kb/en/library/";
        $Zf['sql'] = (isset($Zf['mariadb']) ? $Zf['mariadb'] : str_replace(".html", "/", $Zf['sql']));
    }
    return ($Zf[$y] ? "<a href='$Oi[$y]$Zf[$y]'" . target_blank() . ">$di</a>" : "");
}

function
ob_gzencode($P)
{
    return
        gzencode($P);
}

function
db_size($m)
{
    global $g;
    if (!$g->select_db($m)) return "?";
    $I = 0;
    foreach (table_status() as $R) $I += $R["Data_length"] + $R["Index_length"];
    return
        format_number($I);
}

function
set_utf8mb4($i)
{
    global $g;
    static $O = false;
    if (!$O && preg_match('~\butf8mb4~i', $i)) {
        $O = true;
        echo "SET NAMES " . charset($g) . ";\n\n";
    }
}

function
connect_error()
{
    global $b, $g, $pi, $o, $ec;
    if (DB != "") {
        header("HTTP/1.1 404 Not Found");
        page_header('Database' . ": " . h(DB), 'Invalid database.', true);
    } else {
        if ($_POST["db"] && !$o) queries_redirect(substr(ME, 0, -1), 'Databases have been dropped.', drop_databases($_POST["db"]));
        page_header('Select database', $o, false);
        echo "<p class='links'>\n";
        foreach (array('database' => 'Create database', 'privileges' => 'Privileges', 'processlist' => 'Process list', 'variables' => 'Variables', 'status' => 'Status',) as $z => $X) {
            if (support($z)) echo "<a href='" . h(ME) . "$z='>$X</a>\n";
        }
        echo "<p>" . sprintf('%s version: %s through PHP extension %s', $ec[DRIVER], "<b>" . h($g->server_info) . "</b>", "<b>$g->extension</b>") . "\n", "<p>" . sprintf('Logged as: %s', "<b>" . h(logged_user()) . "</b>") . "\n";
        $l = $b->databases();
        if ($l) {
            $bh = support("scheme");
            $pb = collations();
            echo "<form action='' method='post'>\n", "<table cellspacing='0' class='checkable'>\n", script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"), "<thead><tr>" . (support("database") ? "<td>" : "") . "<th>" . 'Database' . " - <a href='" . h(ME) . "refresh=1'>" . 'Refresh' . "</a>" . "<td>" . 'Collation' . "<td>" . 'Tables' . "<td>" . 'Size' . " - <a href='" . h(ME) . "dbsize=1'>" . 'Compute' . "</a>" . script("qsl('a').onclick = partial(ajaxSetHtml, '" . js_escape(ME) . "script=connect');", "") . "</thead>\n";
            $l = ($_GET["dbsize"] ? count_tables($l) : array_flip($l));
            foreach ($l
                     as $m => $S) {
                $Tg = h(ME) . "db=" . urlencode($m);
                $u = h("Db-" . $m);
                echo "<tr" . odd() . ">" . (support("database") ? "<td>" . checkbox("db[]", $m, in_array($m, (array)$_POST["db"]), "", "", "", $u) : ""), "<th><a href='$Tg' id='$u'>" . h($m) . "</a>";
                $d = h(db_collation($m, $pb));
                echo "<td>" . (support("database") ? "<a href='$Tg" . ($bh ? "&amp;ns=" : "") . "&amp;database=' title='" . 'Alter database' . "'>$d</a>" : $d), "<td align='right'><a href='$Tg&amp;schema=' id='tables-" . h($m) . "' title='" . 'Database schema' . "'>" . ($_GET["dbsize"] ? $S : "?") . "</a>", "<td align='right' id='size-" . h($m) . "'>" . ($_GET["dbsize"] ? db_size($m) : "?"), "\n";
            }
            echo "</table>\n", (support("database") ? "<div class='footer'><div>\n" . "<fieldset><legend>" . 'Selected' . " <span id='selected'></span></legend><div>\n" . "<input type='hidden' name='all' value=''>" . script("qsl('input').onclick = function () { selectCount('selected', formChecked(this, /^db/)); };") . "<input type='submit' name='drop' value='" . 'Drop' . "'>" . confirm() . "\n" . "</div></fieldset>\n" . "</div></div>\n" : ""), "<input type='hidden' name='token' value='$pi'>\n", "</form>\n", script("tableCheck();");
        }
    }
    page_footer("db");
}

if (isset($_GET["status"])) $_GET["variables"] = $_GET["status"];
if (isset($_GET["import"])) $_GET["sql"] = $_GET["import"];
if (!(DB != "" ? $g->select_db(DB) : isset($_GET["sql"]) || isset($_GET["dump"]) || isset($_GET["database"]) || isset($_GET["processlist"]) || isset($_GET["privileges"]) || isset($_GET["user"]) || isset($_GET["variables"]) || $_GET["script"] == "connect" || $_GET["script"] == "kill")) {
    if (DB != "" || $_GET["refresh"]) {
        restart_session();
        set_session("dbs", null);
    }
    connect_error();
    exit;
}
if (support("scheme") && DB != "" && $_GET["ns"] !== "") {
    if (!isset($_GET["ns"])) redirect(preg_replace('~ns=[^&]*&~', '', ME) . "ns=" . get_schema());
    if (!set_schema($_GET["ns"])) {
        header("HTTP/1.1 404 Not Found");
        page_header('Schema' . ": " . h($_GET["ns"]), 'Invalid schema.', true);
        page_footer("ns");
        exit;
    }
}
$qf = "RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";

class
TmpFile
{
    var $handler;
    var $size;

    function
    __construct()
    {
        $this->handler = tmpfile();
    }

    function
    write($Ab)
    {
        $this->size += strlen($Ab);
        fwrite($this->handler, $Ab);
    }

    function
    send()
    {
        fseek($this->handler, 0);
        fpassthru($this->handler);
        fclose($this->handler);
    }
}

$wc = "'(?:''|[^'\\\\]|\\\\.)*'";
$Rd = "IN|OUT|INOUT";
if (isset($_GET["select"]) && ($_POST["edit"] || $_POST["clone"]) && !$_POST["save"]) $_GET["edit"] = $_GET["select"];
if (isset($_GET["callf"])) $_GET["call"] = $_GET["callf"];
if (isset($_GET["function"])) $_GET["procedure"] = $_GET["function"];
if (isset($_GET["download"])) {
    $a = $_GET["download"];
    $q = fields($a);
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=" . friendly_url("$a-" . implode("_", $_GET["where"])) . "." . friendly_url($_GET["field"]));
    $L = array(idf_escape($_GET["field"]));
    $H = $n->select($a, $L, array(where($_GET, $q)), $L);
    $J = ($H ? $H->fetch_row() : array());
    echo $n->value($J[0], $q[$_GET["field"]]);
    exit;
} elseif (isset($_GET["table"])) {
    $a = $_GET["table"];
    $q = fields($a);
    if (!$q) $o = error();
    $R = table_status1($a, true);
    $C = $b->tableName($R);
    page_header(($q && is_view($R) ? $R['Engine'] == 'materialized view' ? 'Materialized view' : 'View' : 'Table') . ": " . ($C != "" ? $C : h($a)), $o);
    $b->selectLinks($R);
    $ub = $R["Comment"];
    if ($ub != "") echo "<p class='nowrap'>" . 'Comment' . ": " . h($ub) . "\n";
    if ($q) $b->tableStructurePrint($q);
    if (!is_view($R)) {
        if (support("indexes")) {
            echo "<h3 id='indexes'>" . 'Indexes' . "</h3>\n";
            $x = indexes($a);
            if ($x) $b->tableIndexesPrint($x);
            echo '<p class="links"><a href="' . h(ME) . 'indexes=' . urlencode($a) . '">' . 'Alter indexes' . "</a>\n";
        }
        if (fk_support($R)) {
            echo "<h3 id='foreign-keys'>" . 'Foreign keys' . "</h3>\n";
            $ed = foreign_keys($a);
            if ($ed) {
                echo "<table cellspacing='0'>\n", "<thead><tr><th>" . 'Source' . "<td>" . 'Target' . "<td>" . 'ON DELETE' . "<td>" . 'ON UPDATE' . "<td></thead>\n";
                foreach ($ed
                         as $C => $r) {
                    echo "<tr title='" . h($C) . "'>", "<th><i>" . implode("</i>, <i>", array_map('h', $r["source"])) . "</i>", "<td><a href='" . h($r["db"] != "" ? preg_replace('~db=[^&]*~', "db=" . urlencode($r["db"]), ME) : ($r["ns"] != "" ? preg_replace('~ns=[^&]*~', "ns=" . urlencode($r["ns"]), ME) : ME)) . "table=" . urlencode($r["table"]) . "'>" . ($r["db"] != "" ? "<b>" . h($r["db"]) . "</b>." : "") . ($r["ns"] != "" ? "<b>" . h($r["ns"]) . "</b>." : "") . h($r["table"]) . "</a>", "(<i>" . implode("</i>, <i>", array_map('h', $r["target"])) . "</i>)", "<td>" . h($r["on_delete"]) . "\n", "<td>" . h($r["on_update"]) . "\n", '<td><a href="' . h(ME . 'foreign=' . urlencode($a) . '&name=' . urlencode($C)) . '">' . 'Alter' . '</a>';
                }
                echo "</table>\n";
            }
            echo '<p class="links"><a href="' . h(ME) . 'foreign=' . urlencode($a) . '">' . 'Add foreign key' . "</a>\n";
        }
    }
    if (support(is_view($R) ? "view_trigger" : "trigger")) {
        echo "<h3 id='triggers'>" . 'Triggers' . "</h3>\n";
        $Ai = triggers($a);
        if ($Ai) {
            echo "<table cellspacing='0'>\n";
            foreach ($Ai
                     as $z => $X) echo "<tr valign='top'><td>" . h($X[0]) . "<td>" . h($X[1]) . "<th>" . h($z) . "<td><a href='" . h(ME . 'trigger=' . urlencode($a) . '&name=' . urlencode($z)) . "'>" . 'Alter' . "</a>\n";
            echo "</table>\n";
        }
        echo '<p class="links"><a href="' . h(ME) . 'trigger=' . urlencode($a) . '">' . 'Add trigger' . "</a>\n";
    }
} elseif (isset($_GET["schema"])) {
    page_header('Database schema', "", array(), h(DB . ($_GET["ns"] ? ".$_GET[ns]" : "")));
    $Sh = array();
    $Th = array();
    $ea = ($_GET["schema"] ? $_GET["schema"] : $_COOKIE["adminer_schema-" . str_replace(".", "_", DB)]);
    preg_match_all('~([^:]+):([-0-9.]+)x([-0-9.]+)(_|$)~', $ea, $De, PREG_SET_ORDER);
    foreach ($De
             as $t => $B) {
        $Sh[$B[1]] = array($B[2], $B[3]);
        $Th[] = "\n\t'" . js_escape($B[1]) . "': [ $B[2], $B[3] ]";
    }
    $qi = 0;
    $Ra = -1;
    $ah = array();
    $Fg = array();
    $re = array();
    foreach (table_status('', true) as $Q => $R) {
        if (is_view($R)) continue;
        $eg = 0;
        $ah[$Q]["fields"] = array();
        foreach (fields($Q) as $C => $p) {
            $eg += 1.25;
            $p["pos"] = $eg;
            $ah[$Q]["fields"][$C] = $p;
        }
        $ah[$Q]["pos"] = ($Sh[$Q] ? $Sh[$Q] : array($qi, 0));
        foreach ($b->foreignKeys($Q) as $X) {
            if (!$X["db"]) {
                $pe = $Ra;
                if ($Sh[$Q][1] || $Sh[$X["table"]][1]) $pe = min(floatval($Sh[$Q][1]), floatval($Sh[$X["table"]][1])) - 1; else$Ra -= .1;
                while ($re[(string)$pe]) $pe -= .0001;
                $ah[$Q]["references"][$X["table"]][(string)$pe] = array($X["source"], $X["target"]);
                $Fg[$X["table"]][$Q][(string)$pe] = $X["target"];
                $re[(string)$pe] = true;
            }
        }
        $qi = max($qi, $ah[$Q]["pos"][0] + 2.5 + $eg);
    }
    echo '<div id="schema" style="height: ', $qi, 'em;">
<script', nonce(), '>
qs(\'#schema\').onselectstart = function () { return false; };
var tablePos = {', implode(",", $Th) . "\n", '};
var em = qs(\'#schema\').offsetHeight / ', $qi, ';
document.onmousemove = schemaMousemove;
document.onmouseup = partialArg(schemaMouseup, \'', js_escape(DB), '\');
</script>
';
    foreach ($ah
             as $C => $Q) {
        echo "<div class='table' style='top: " . $Q["pos"][0] . "em; left: " . $Q["pos"][1] . "em;'>", '<a href="' . h(ME) . 'table=' . urlencode($C) . '"><b>' . h($C) . "</b></a>", script("qsl('div').onmousedown = schemaMousedown;");
        foreach ($Q["fields"] as $p) {
            $X = '<span' . type_class($p["type"]) . ' title="' . h($p["full_type"] . ($p["null"] ? " NULL" : '')) . '">' . h($p["field"]) . '</span>';
            echo "<br>" . ($p["primary"] ? "<i>$X</i>" : $X);
        }
        foreach ((array)$Q["references"] as $Zh => $Gg) {
            foreach ($Gg
                     as $pe => $Cg) {
                $qe = $pe - $Sh[$C][1];
                $t = 0;
                foreach ($Cg[0] as $wh) echo "\n<div class='references' title='" . h($Zh) . "' id='refs$pe-" . ($t++) . "' style='left: $qe" . "em; top: " . $Q["fields"][$wh]["pos"] . "em; padding-top: .5em;'><div style='border-top: 1px solid Gray; width: " . (-$qe) . "em;'></div></div>";
            }
        }
        foreach ((array)$Fg[$C] as $Zh => $Gg) {
            foreach ($Gg
                     as $pe => $f) {
                $qe = $pe - $Sh[$C][1];
                $t = 0;
                foreach ($f
                         as $Yh) echo "\n<div class='references' title='" . h($Zh) . "' id='refd$pe-" . ($t++) . "' style='left: $qe" . "em; top: " . $Q["fields"][$Yh]["pos"] . "em; height: 1.25em; background: url(" . h(preg_replace("~\\?.*~", "", ME) . "?file=arrow.gif) no-repeat right center;&version=4.7.3") . "'><div style='height: .5em; border-bottom: 1px solid Gray; width: " . (-$qe) . "em;'></div></div>";
            }
        }
        echo "\n</div>\n";
    }
    foreach ($ah
             as $C => $Q) {
        foreach ((array)$Q["references"] as $Zh => $Gg) {
            foreach ($Gg
                     as $pe => $Cg) {
                $Se = $qi;
                $He = -10;
                foreach ($Cg[0] as $z => $wh) {
                    $fg = $Q["pos"][0] + $Q["fields"][$wh]["pos"];
                    $gg = $ah[$Zh]["pos"][0] + $ah[$Zh]["fields"][$Cg[1][$z]]["pos"];
                    $Se = min($Se, $fg, $gg);
                    $He = max($He, $fg, $gg);
                }
                echo "<div class='references' id='refl$pe' style='left: $pe" . "em; top: $Se" . "em; padding: .5em 0;'><div style='border-right: 1px solid Gray; margin-top: 1px; height: " . ($He - $Se) . "em;'></div></div>\n";
            }
        }
    }
    echo '</div>
<p class="links"><a href="', h(ME . "schema=" . urlencode($ea)), '" id="schema-link">Permanent link</a>
';
} elseif (isset($_GET["dump"])) {
    $a = $_GET["dump"];
    if ($_POST && !$o) {
        $Db = "";
        foreach (array("output", "format", "db_style", "routines", "events", "table_style", "auto_increment", "triggers", "data_style") as $z) $Db .= "&$z=" . urlencode($_POST[$z]);
        cookie("adminer_export", substr($Db, 1));
        $S = array_flip((array)$_POST["tables"]) + array_flip((array)$_POST["data"]);
        $Ic = dump_headers((count($S) == 1 ? key($S) : DB), (DB == "" || count($S) > 1));
        $Zd = preg_match('~sql~', $_POST["format"]);
        if ($Zd) {
            echo "-- Adminer $ia " . $ec[DRIVER] . " dump\n\n";
            if ($y == "sql") {
                echo "SET NAMES utf8;
SET time_zone = '+00:00';
" . ($_POST["data_style"] ? "SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
" : "") . "
";
                $g->query("SET time_zone = '+00:00';");
            }
        }
        $Jh = $_POST["db_style"];
        $l = array(DB);
        if (DB == "") {
            $l = $_POST["databases"];
            if (is_string($l)) $l = explode("\n", rtrim(str_replace("\r", "", $l), "\n"));
        }
        foreach ((array)$l
                 as $m) {
            $b->dumpDatabase($m);
            if ($g->select_db($m)) {
                if ($Zd && preg_match('~CREATE~', $Jh) && ($i = $g->result("SHOW CREATE DATABASE " . idf_escape($m), 1))) {
                    set_utf8mb4($i);
                    if ($Jh == "DROP+CREATE") echo "DROP DATABASE IF EXISTS " . idf_escape($m) . ";\n";
                    echo "$i;\n";
                }
                if ($Zd) {
                    if ($Jh) echo
                        use_sql($m) . ";\n\n";
                    $Jf = "";
                    if ($_POST["routines"]) {
                        foreach (array("FUNCTION", "PROCEDURE") as $Ug) {
                            foreach (get_rows("SHOW $Ug STATUS WHERE Db = " . q($m), null, "-- ") as $J) {
                                $i = remove_definer($g->result("SHOW CREATE $Ug " . idf_escape($J["Name"]), 2));
                                set_utf8mb4($i);
                                $Jf .= ($Jh != 'DROP+CREATE' ? "DROP $Ug IF EXISTS " . idf_escape($J["Name"]) . ";;\n" : "") . "$i;;\n\n";
                            }
                        }
                    }
                    if ($_POST["events"]) {
                        foreach (get_rows("SHOW EVENTS", null, "-- ") as $J) {
                            $i = remove_definer($g->result("SHOW CREATE EVENT " . idf_escape($J["Name"]), 3));
                            set_utf8mb4($i);
                            $Jf .= ($Jh != 'DROP+CREATE' ? "DROP EVENT IF EXISTS " . idf_escape($J["Name"]) . ";;\n" : "") . "$i;;\n\n";
                        }
                    }
                    if ($Jf) echo "DELIMITER ;;\n\n$Jf" . "DELIMITER ;\n\n";
                }
                if ($_POST["table_style"] || $_POST["data_style"]) {
                    $bj = array();
                    foreach (table_status('', true) as $C => $R) {
                        $Q = (DB == "" || in_array($C, (array)$_POST["tables"]));
                        $Lb = (DB == "" || in_array($C, (array)$_POST["data"]));
                        if ($Q || $Lb) {
                            if ($Ic == "tar") {
                                $mi = new
                                TmpFile;
                                ob_start(array($mi, 'write'), 1e5);
                            }
                            $b->dumpTable($C, ($Q ? $_POST["table_style"] : ""), (is_view($R) ? 2 : 0));
                            if (is_view($R)) $bj[] = $C; elseif ($Lb) {
                                $q = fields($C);
                                $b->dumpData($C, $_POST["data_style"], "SELECT *" . convert_fields($q, $q) . " FROM " . table($C));
                            }
                            if ($Zd && $_POST["triggers"] && $Q && ($Ai = trigger_sql($C))) echo "\nDELIMITER ;;\n$Ai\nDELIMITER ;\n";
                            if ($Ic == "tar") {
                                ob_end_flush();
                                tar_file((DB != "" ? "" : "$m/") . "$C.csv", $mi);
                            } elseif ($Zd) echo "\n";
                        }
                    }
                    foreach ($bj
                             as $aj) $b->dumpTable($aj, $_POST["table_style"], 1);
                    if ($Ic == "tar") echo
                    pack("x512");
                }
            }
        }
        if ($Zd) echo "-- " . $g->result("SELECT NOW()") . "\n";
        exit;
    }
    page_header('Export', $o, ($_GET["export"] != "" ? array("table" => $_GET["export"]) : array()), h(DB));
    echo '
<form action="" method="post">
<table cellspacing="0" class="layout">
';
    $Pb = array('', 'USE', 'DROP+CREATE', 'CREATE');
    $Uh = array('', 'DROP+CREATE', 'CREATE');
    $Mb = array('', 'TRUNCATE+INSERT', 'INSERT');
    if ($y == "sql") $Mb[] = 'INSERT+UPDATE';
    parse_str($_COOKIE["adminer_export"], $J);
    if (!$J) $J = array("output" => "text", "format" => "sql", "db_style" => (DB != "" ? "" : "CREATE"), "table_style" => "DROP+CREATE", "data_style" => "INSERT");
    if (!isset($J["events"])) {
        $J["routines"] = $J["events"] = ($_GET["dump"] == "");
        $J["triggers"] = $J["table_style"];
    }
    echo "<tr><th>" . 'Output' . "<td>" . html_select("output", $b->dumpOutput(), $J["output"], 0) . "\n";
    echo "<tr><th>" . 'Format' . "<td>" . html_select("format", $b->dumpFormat(), $J["format"], 0) . "\n";
    echo($y == "sqlite" ? "" : "<tr><th>" . 'Database' . "<td>" . html_select('db_style', $Pb, $J["db_style"]) . (support("routine") ? checkbox("routines", 1, $J["routines"], 'Routines') : "") . (support("event") ? checkbox("events", 1, $J["events"], 'Events') : "")), "<tr><th>" . 'Tables' . "<td>" . html_select('table_style', $Uh, $J["table_style"]) . checkbox("auto_increment", 1, $J["auto_increment"], 'Auto Increment') . (support("trigger") ? checkbox("triggers", 1, $J["triggers"], 'Triggers') : ""), "<tr><th>" . 'Data' . "<td>" . html_select('data_style', $Mb, $J["data_style"]), '</table>
<p><input type="submit" value="Export">
<input type="hidden" name="token" value="', $pi, '">

<table cellspacing="0">
', script("qsl('table').onclick = dumpClick;");
    $jg = array();
    if (DB != "") {
        $fb = ($a != "" ? "" : " checked");
        echo "<thead><tr>", "<th style='text-align: left;'><label class='block'><input type='checkbox' id='check-tables'$fb>" . 'Tables' . "</label>" . script("qs('#check-tables').onclick = partial(formCheck, /^tables\\[/);", ""), "<th style='text-align: right;'><label class='block'>" . 'Data' . "<input type='checkbox' id='check-data'$fb></label>" . script("qs('#check-data').onclick = partial(formCheck, /^data\\[/);", ""), "</thead>\n";
        $bj = "";
        $Vh = tables_list();
        foreach ($Vh
                 as $C => $T) {
            $ig = preg_replace('~_.*~', '', $C);
            $fb = ($a == "" || $a == (substr($a, -1) == "%" ? "$ig%" : $C));
            $mg = "<tr><td>" . checkbox("tables[]", $C, $fb, $C, "", "block");
            if ($T !== null && !preg_match('~table~i', $T)) $bj .= "$mg\n"; else
                echo "$mg<td align='right'><label class='block'><span id='Rows-" . h($C) . "'></span>" . checkbox("data[]", $C, $fb) . "</label>\n";
            $jg[$ig]++;
        }
        echo $bj;
        if ($Vh) echo
        script("ajaxSetHtml('" . js_escape(ME) . "script=db');");
    } else {
        echo "<thead><tr><th style='text-align: left;'>", "<label class='block'><input type='checkbox' id='check-databases'" . ($a == "" ? " checked" : "") . ">" . 'Database' . "</label>", script("qs('#check-databases').onclick = partial(formCheck, /^databases\\[/);", ""), "</thead>\n";
        $l = $b->databases();
        if ($l) {
            foreach ($l
                     as $m) {
                if (!information_schema($m)) {
                    $ig = preg_replace('~_.*~', '', $m);
                    echo "<tr><td>" . checkbox("databases[]", $m, $a == "" || $a == "$ig%", $m, "", "block") . "\n";
                    $jg[$ig]++;
                }
            }
        } else
            echo "<tr><td><textarea name='databases' rows='10' cols='20'></textarea>";
    }
    echo '</table>
</form>
';
    $Wc = true;
    foreach ($jg
             as $z => $X) {
        if ($z != "" && $X > 1) {
            echo ($Wc ? "<p>" : " ") . "<a href='" . h(ME) . "dump=" . urlencode("$z%") . "'>" . h($z) . "</a>";
            $Wc = false;
        }
    }
} elseif (isset($_GET["privileges"])) {
    page_header('Privileges');
    echo '<p class="links"><a href="' . h(ME) . 'user=">' . 'Create user' . "</a>";
    $H = $g->query("SELECT User, Host FROM mysql." . (DB == "" ? "user" : "db WHERE " . q(DB) . " LIKE Db") . " ORDER BY Host, User");
    $ld = $H;
    if (!$H) $H = $g->query("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1) AS User, SUBSTRING_INDEX(CURRENT_USER, '@', -1) AS Host");
    echo "<form action=''><p>\n";
    hidden_fields_get();
    echo "<input type='hidden' name='db' value='" . h(DB) . "'>\n", ($ld ? "" : "<input type='hidden' name='grant' value=''>\n"), "<table cellspacing='0'>\n", "<thead><tr><th>" . 'Username' . "<th>" . 'Server' . "<th></thead>\n";
    while ($J = $H->fetch_assoc()) echo '<tr' . odd() . '><td>' . h($J["User"]) . "<td>" . h($J["Host"]) . '<td><a href="' . h(ME . 'user=' . urlencode($J["User"]) . '&host=' . urlencode($J["Host"])) . '">' . 'Edit' . "</a>\n";
    if (!$ld || DB != "") echo "<tr" . odd() . "><td><input name='user' autocapitalize='off'><td><input name='host' value='localhost' autocapitalize='off'><td><input type='submit' value='" . 'Edit' . "'>\n";
    echo "</table>\n", "</form>\n";
} elseif (isset($_GET["sql"])) {
    if (!$o && $_POST["export"]) {
        dump_headers("sql");
        $b->dumpTable("", "");
        $b->dumpData("", "table", $_POST["query"]);
        exit;
    }
    restart_session();
    $zd =& get_session("queries");
    $yd =& $zd[DB];
    if (!$o && $_POST["clear"]) {
        $yd = array();
        redirect(remove_from_uri("history"));
    }
    page_header((isset($_GET["import"]) ? 'Import' : 'SQL command'), $o);
    if (!$o && $_POST) {
        $id = false;
        if (!isset($_GET["import"])) $G = $_POST["query"]; elseif ($_POST["webfile"]) {
            $_h = $b->importServerPath();
            $id = @fopen((file_exists($_h) ? $_h : "compress.zlib://$_h.gz"), "rb");
            $G = ($id ? fread($id, 1e6) : false);
        } else$G = get_file("sql_file", true);
        if (is_string($G)) {
            if (function_exists('memory_get_usage')) @ini_set("memory_limit", max(ini_bytes("memory_limit"), 2 * strlen($G) + memory_get_usage() + 8e6));
            if ($G != "" && strlen($G) < 1e6) {
                $ug = $G . (preg_match("~;[ \t\r\n]*\$~", $G) ? "" : ";");
                if (!$yd || reset(end($yd)) != $ug) {
                    restart_session();
                    $yd[] = array($ug, time());
                    set_session("queries", $zd);
                    stop_session();
                }
            }
            $xh = "(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";
            $Vb = ";";
            $D = 0;
            $tc = true;
            $h = connect();
            if (is_object($h) && DB != "") $h->select_db(DB);
            $tb = 0;
            $yc = array();
            $Qf = '[\'"' . ($y == "sql" ? '`#' : ($y == "sqlite" ? '`[' : ($y == "mssql" ? '[' : ''))) . ']|/\*|-- |$' . ($y == "pgsql" ? '|\$[^$]*\$' : '');
            $ri = microtime(true);
            parse_str($_COOKIE["adminer_export"], $xa);
            $kc = $b->dumpFormat();
            unset($kc["sql"]);
            while ($G != "") {
                if (!$D && preg_match("~^$xh*+DELIMITER\\s+(\\S+)~i", $G, $B)) {
                    $Vb = $B[1];
                    $G = substr($G, strlen($B[0]));
                } else {
                    preg_match('(' . preg_quote($Vb) . "\\s*|$Qf)", $G, $B, PREG_OFFSET_CAPTURE, $D);
                    list($gd, $eg) = $B[0];
                    if (!$gd && $id && !feof($id)) $G .= fread($id, 1e5); else {
                        if (!$gd && rtrim($G) == "") break;
                        $D = $eg + strlen($gd);
                        if ($gd && rtrim($gd) != $Vb) {
                            while (preg_match('(' . ($gd == '/*' ? '\*/' : ($gd == '[' ? ']' : (preg_match('~^-- |^#~', $gd) ? "\n" : preg_quote($gd) . "|\\\\."))) . '|$)s', $G, $B, PREG_OFFSET_CAPTURE, $D)) {
                                $Yg = $B[0][0];
                                if (!$Yg && $id && !feof($id)) $G .= fread($id, 1e5); else {
                                    $D = $B[0][1] + strlen($Yg);
                                    if ($Yg[0] != "\\") break;
                                }
                            }
                        } else {
                            $tc = false;
                            $ug = substr($G, 0, $eg);
                            $tb++;
                            $mg = "<pre id='sql-$tb'><code class='jush-$y'>" . $b->sqlCommandQuery($ug) . "</code></pre>\n";
                            if ($y == "sqlite" && preg_match("~^$xh*+ATTACH\\b~i", $ug, $B)) {
                                echo $mg, "<p class='error'>" . 'ATTACH queries are not supported.' . "\n";
                                $yc[] = " <a href='#sql-$tb'>$tb</a>";
                                if ($_POST["error_stops"]) break;
                            } else {
                                if (!$_POST["only_errors"]) {
                                    echo $mg;
                                    ob_flush();
                                    flush();
                                }
                                $Dh = microtime(true);
                                if ($g->multi_query($ug) && is_object($h) && preg_match("~^$xh*+USE\\b~i", $ug)) $h->query($ug);
                                do {
                                    $H = $g->store_result();
                                    if ($g->error) {
                                        echo($_POST["only_errors"] ? $mg : ""), "<p class='error'>" . 'Error in query' . ($g->errno ? " ($g->errno)" : "") . ": " . error() . "\n";
                                        $yc[] = " <a href='#sql-$tb'>$tb</a>";
                                        if ($_POST["error_stops"]) break
                                        2;
                                    } else {
                                        $fi = " <span class='time'>(" . format_time($Dh) . ")</span>" . (strlen($ug) < 1000 ? " <a href='" . h(ME) . "sql=" . urlencode(trim($ug)) . "'>" . 'Edit' . "</a>" : "");
                                        $za = $g->affected_rows;
                                        $ej = ($_POST["only_errors"] ? "" : $n->warnings());
                                        $fj = "warnings-$tb";
                                        if ($ej) $fi .= ", <a href='#$fj'>" . 'Warnings' . "</a>" . script("qsl('a').onclick = partial(toggle, '$fj');", "");
                                        $Fc = null;
                                        $Gc = "explain-$tb";
                                        if (is_object($H)) {
                                            $_ = $_POST["limit"];
                                            $Cf = select($H, $h, array(), $_);
                                            if (!$_POST["only_errors"]) {
                                                echo "<form action='' method='post'>\n";
                                                $gf = $H->num_rows;
                                                echo "<p>" . ($gf ? ($_ && $gf > $_ ? sprintf('%d / ', $_) : "") . lang(array('%d row', '%d rows'), $gf) : ""), $fi;
                                                if ($h && preg_match("~^($xh|\\()*+SELECT\\b~i", $ug) && ($Fc = explain($h, $ug))) echo ", <a href='#$Gc'>Explain</a>" . script("qsl('a').onclick = partial(toggle, '$Gc');", "");
                                                $u = "export-$tb";
                                                echo ", <a href='#$u'>" . 'Export' . "</a>" . script("qsl('a').onclick = partial(toggle, '$u');", "") . "<span id='$u' class='hidden'>: " . html_select("output", $b->dumpOutput(), $xa["output"]) . " " . html_select("format", $kc, $xa["format"]) . "<input type='hidden' name='query' value='" . h($ug) . "'>" . " <input type='submit' name='export' value='" . 'Export' . "'><input type='hidden' name='token' value='$pi'></span>\n" . "</form>\n";
                                            }
                                        } else {
                                            if (preg_match("~^$xh*+(CREATE|DROP|ALTER)$xh++(DATABASE|SCHEMA)\\b~i", $ug)) {
                                                restart_session();
                                                set_session("dbs", null);
                                                stop_session();
                                            }
                                            if (!$_POST["only_errors"]) echo "<p class='message' title='" . h($g->info) . "'>" . lang(array('Query executed OK, %d row affected.', 'Query executed OK, %d rows affected.'), $za) . "$fi\n";
                                        }
                                        echo($ej ? "<div id='$fj' class='hidden'>\n$ej</div>\n" : "");
                                        if ($Fc) {
                                            echo "<div id='$Gc' class='hidden'>\n";
                                            select($Fc, $h, $Cf);
                                            echo "</div>\n";
                                        }
                                    }
                                    $Dh = microtime(true);
                                } while ($g->next_result());
                            }
                            $G = substr($G, $D);
                            $D = 0;
                        }
                    }
                }
            }
            if ($tc) echo "<p class='message'>" . 'No commands to execute.' . "\n"; elseif ($_POST["only_errors"]) {
                echo "<p class='message'>" . lang(array('%d query executed OK.', '%d queries executed OK.'), $tb - count($yc)), " <span class='time'>(" . format_time($ri) . ")</span>\n";
            } elseif ($yc && $tb > 1) echo "<p class='error'>" . 'Error in query' . ": " . implode("", $yc) . "\n";
        } else
            echo "<p class='error'>" . upload_error($G) . "\n";
    }
    echo '
<form action="" method="post" enctype="multipart/form-data" id="form">
';
    $Cc = "<input type='submit' value='" . 'Execute' . "' title='Ctrl+Enter'>";
    if (!isset($_GET["import"])) {
        $ug = $_GET["sql"];
        if ($_POST) $ug = $_POST["query"]; elseif ($_GET["history"] == "all") $ug = $yd;
        elseif ($_GET["history"] != "") $ug = $yd[$_GET["history"]][0];
        echo "<p>";
        textarea("query", $ug, 20);
        echo
        script(($_POST ? "" : "qs('textarea').focus();\n") . "qs('#form').onsubmit = partial(sqlSubmit, qs('#form'), '" . remove_from_uri("sql|limit|error_stops|only_errors") . "');"), "<p>$Cc\n", 'Limit rows' . ": <input type='number' name='limit' class='size' value='" . h($_POST ? $_POST["limit"] : $_GET["limit"]) . "'>\n";
    } else {
        echo "<fieldset><legend>" . 'File upload' . "</legend><div>";
        $rd = (extension_loaded("zlib") ? "[.gz]" : "");
        echo(ini_bool("file_uploads") ? "SQL$rd (&lt; " . ini_get("upload_max_filesize") . "B): <input type='file' name='sql_file[]' multiple>\n$Cc" : 'File uploads are disabled.'), "</div></fieldset>\n";
        $Gd = $b->importServerPath();
        if ($Gd) {
            echo "<fieldset><legend>" . 'From server' . "</legend><div>", sprintf('Webserver file %s', "<code>" . h($Gd) . "$rd</code>"), ' <input type="submit" name="webfile" value="' . 'Run file' . '">', "</div></fieldset>\n";
        }
        echo "<p>";
    }
    echo
        checkbox("error_stops", 1, ($_POST ? $_POST["error_stops"] : isset($_GET["import"])), 'Stop on error') . "\n", checkbox("only_errors", 1, ($_POST ? $_POST["only_errors"] : isset($_GET["import"])), 'Show only errors') . "\n", "<input type='hidden' name='token' value='$pi'>\n";
    if (!isset($_GET["import"]) && $yd) {
        print_fieldset("history", 'History', $_GET["history"] != "");
        for ($X = end($yd); $X; $X = prev($yd)) {
            $z = key($yd);
            list($ug, $fi, $oc) = $X;
            echo '<a href="' . h(ME . "sql=&history=$z") . '">' . 'Edit' . "</a>" . " <span class='time' title='" . @date('Y-m-d', $fi) . "'>" . @date("H:i:s", $fi) . "</span>" . " <code class='jush-$y'>" . shorten_utf8(ltrim(str_replace("\n", " ", str_replace("\r", "", preg_replace('~^(#|-- ).*~m', '', $ug)))), 80, "</code>") . ($oc ? " <span class='time'>($oc)</span>" : "") . "<br>\n";
        }
        echo "<input type='submit' name='clear' value='" . 'Clear' . "'>\n", "<a href='" . h(ME . "sql=&history=all") . "'>" . 'Edit all' . "</a>\n", "</div></fieldset>\n";
    }
    echo '</form>
';
} elseif (isset($_GET["edit"])) {
    $a = $_GET["edit"];
    $q = fields($a);
    $Z = (isset($_GET["select"]) ? ($_POST["check"] && count($_POST["check"]) == 1 ? where_check($_POST["check"][0], $q) : "") : where($_GET, $q));
    $Ki = (isset($_GET["select"]) ? $_POST["edit"] : $Z);
    foreach ($q
             as $C => $p) {
        if (!isset($p["privileges"][$Ki ? "update" : "insert"]) || $b->fieldName($p) == "" || $p["generated"]) unset($q[$C]);
    }
    if ($_POST && !$o && !isset($_GET["select"])) {
        $xe = $_POST["referer"];
        if ($_POST["insert"]) $xe = ($Ki ? null : $_SERVER["REQUEST_URI"]); elseif (!preg_match('~^.+&select=.+$~', $xe)) $xe = ME . "select=" . urlencode($a);
        $x = indexes($a);
        $Fi = unique_array($_GET["where"], $x);
        $xg = "\nWHERE $Z";
        if (isset($_POST["delete"])) queries_redirect($xe, 'Item has been deleted.', $n->delete($a, $xg, !$Fi)); else {
            $O = array();
            foreach ($q
                     as $C => $p) {
                $X = process_input($p);
                if ($X !== false && $X !== null) $O[idf_escape($C)] = $X;
            }
            if ($Ki) {
                if (!$O) redirect($xe);
                queries_redirect($xe, 'Item has been updated.', $n->update($a, $O, $xg, !$Fi));
                if (is_ajax()) {
                    page_headers();
                    page_messages($o);
                    exit;
                }
            } else {
                $H = $n->insert($a, $O);
                $oe = ($H ? last_id() : 0);
                queries_redirect($xe, sprintf('Item%s has been inserted.', ($oe ? " $oe" : "")), $H);
            }
        }
    }
    $J = null;
    if ($_POST["save"]) $J = (array)$_POST["fields"]; elseif ($Z) {
        $L = array();
        foreach ($q
                 as $C => $p) {
            if (isset($p["privileges"]["select"])) {
                $Ga = convert_field($p);
                if ($_POST["clone"] && $p["auto_increment"]) $Ga = "''";
                if ($y == "sql" && preg_match("~enum|set~", $p["type"])) $Ga = "1*" . idf_escape($C);
                $L[] = ($Ga ? "$Ga AS " : "") . idf_escape($C);
            }
        }
        $J = array();
        if (!support("table")) $L = array("*");
        if ($L) {
            $H = $n->select($a, $L, array($Z), $L, array(), (isset($_GET["select"]) ? 2 : 1));
            if (!$H) $o = error(); else {
                $J = $H->fetch_assoc();
                if (!$J) $J = false;
            }
            if (isset($_GET["select"]) && (!$J || $H->fetch_assoc())) $J = null;
        }
    }
    if (!support("table") && !$q) {
        if (!$Z) {
            $H = $n->select($a, array("*"), $Z, array("*"));
            $J = ($H ? $H->fetch_assoc() : false);
            if (!$J) $J = array($n->primary => "");
        }
        if ($J) {
            foreach ($J
                     as $z => $X) {
                if (!$Z) $J[$z] = null;
                $q[$z] = array("field" => $z, "null" => ($z != $n->primary), "auto_increment" => ($z == $n->primary));
            }
        }
    }
    edit_form($a, $q, $J, $Ki);
} elseif (isset($_GET["create"])) {
    $a = $_GET["create"];
    $Sf = array();
    foreach (array('HASH', 'LINEAR HASH', 'KEY', 'LINEAR KEY', 'RANGE', 'LIST') as $z) $Sf[$z] = $z;
    $Eg = referencable_primary($a);
    $ed = array();
    foreach ($Eg
             as $Qh => $p) $ed[str_replace("`", "``", $Qh) . "`" . str_replace("`", "``", $p["field"])] = $Qh;
    $Ff = array();
    $R = array();
    if ($a != "") {
        $Ff = fields($a);
        $R = table_status($a);
        if (!$R) $o = 'No tables.';
    }
    $J = $_POST;
    $J["fields"] = (array)$J["fields"];
    if ($J["auto_increment_col"]) $J["fields"][$J["auto_increment_col"]]["auto_increment"] = true;
    if ($_POST) set_adminer_settings(array("comments" => $_POST["comments"], "defaults" => $_POST["defaults"]));
    if ($_POST && !process_fields($J["fields"]) && !$o) {
        if ($_POST["drop"]) queries_redirect(substr(ME, 0, -1), 'Table has been dropped.', drop_tables(array($a))); else {
            $q = array();
            $Da = array();
            $Pi = false;
            $cd = array();
            $Ef = reset($Ff);
            $Aa = " FIRST";
            foreach ($J["fields"] as $z => $p) {
                $r = $ed[$p["type"]];
                $Bi = ($r !== null ? $Eg[$r] : $p);
                if ($p["field"] != "") {
                    if (!$p["has_default"]) $p["default"] = null;
                    if ($z == $J["auto_increment_col"]) $p["auto_increment"] = true;
                    $rg = process_field($p, $Bi);
                    $Da[] = array($p["orig"], $rg, $Aa);
                    if ($rg != process_field($Ef, $Ef)) {
                        $q[] = array($p["orig"], $rg, $Aa);
                        if ($p["orig"] != "" || $Aa) $Pi = true;
                    }
                    if ($r !== null) $cd[idf_escape($p["field"])] = ($a != "" && $y != "sqlite" ? "ADD" : " ") . format_foreign_key(array('table' => $ed[$p["type"]], 'source' => array($p["field"]), 'target' => array($Bi["field"]), 'on_delete' => $p["on_delete"],));
                    $Aa = " AFTER " . idf_escape($p["field"]);
                } elseif ($p["orig"] != "") {
                    $Pi = true;
                    $q[] = array($p["orig"]);
                }
                if ($p["orig"] != "") {
                    $Ef = next($Ff);
                    if (!$Ef) $Aa = "";
                }
            }
            $Uf = "";
            if ($Sf[$J["partition_by"]]) {
                $Vf = array();
                if ($J["partition_by"] == 'RANGE' || $J["partition_by"] == 'LIST') {
                    foreach (array_filter($J["partition_names"]) as $z => $X) {
                        $Y = $J["partition_values"][$z];
                        $Vf[] = "\n  PARTITION " . idf_escape($X) . " VALUES " . ($J["partition_by"] == 'RANGE' ? "LESS THAN" : "IN") . ($Y != "" ? " ($Y)" : " MAXVALUE");
                    }
                }
                $Uf .= "\nPARTITION BY $J[partition_by]($J[partition])" . ($Vf ? " (" . implode(",", $Vf) . "\n)" : ($J["partitions"] ? " PARTITIONS " . (+$J["partitions"]) : ""));
            } elseif (support("partitioning") && preg_match("~partitioned~", $R["Create_options"])) $Uf .= "\nREMOVE PARTITIONING";
            $Le = 'Table has been altered.';
            if ($a == "") {
                cookie("adminer_engine", $J["Engine"]);
                $Le = 'Table has been created.';
            }
            $C = trim($J["name"]);
            queries_redirect(ME . (support("table") ? "table=" : "select=") . urlencode($C), $Le, alter_table($a, $C, ($y == "sqlite" && ($Pi || $cd) ? $Da : $q), $cd, ($J["Comment"] != $R["Comment"] ? $J["Comment"] : null), ($J["Engine"] && $J["Engine"] != $R["Engine"] ? $J["Engine"] : ""), ($J["Collation"] && $J["Collation"] != $R["Collation"] ? $J["Collation"] : ""), ($J["Auto_increment"] != "" ? number($J["Auto_increment"]) : ""), $Uf));
        }
    }
    page_header(($a != "" ? 'Alter table' : 'Create table'), $o, array("table" => $a), h($a));
    if (!$_POST) {
        $J = array("Engine" => $_COOKIE["adminer_engine"], "fields" => array(array("field" => "", "type" => (isset($U["int"]) ? "int" : (isset($U["integer"]) ? "integer" : "")), "on_update" => "")), "partition_names" => array(""),);
        if ($a != "") {
            $J = $R;
            $J["name"] = $a;
            $J["fields"] = array();
            if (!$_GET["auto_increment"]) $J["Auto_increment"] = "";
            foreach ($Ff
                     as $p) {
                $p["has_default"] = isset($p["default"]);
                $J["fields"][] = $p;
            }
            if (support("partitioning")) {
                $jd = "FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = " . q(DB) . " AND TABLE_NAME = " . q($a);
                $H = $g->query("SELECT PARTITION_METHOD, PARTITION_ORDINAL_POSITION, PARTITION_EXPRESSION $jd ORDER BY PARTITION_ORDINAL_POSITION DESC LIMIT 1");
                list($J["partition_by"], $J["partitions"], $J["partition"]) = $H->fetch_row();
                $Vf = get_key_vals("SELECT PARTITION_NAME, PARTITION_DESCRIPTION $jd AND PARTITION_NAME != '' ORDER BY PARTITION_ORDINAL_POSITION");
                $Vf[""] = "";
                $J["partition_names"] = array_keys($Vf);
                $J["partition_values"] = array_values($Vf);
            }
        }
    }
    $pb = collations();
    $vc = engines();
    foreach ($vc
             as $uc) {
        if (!strcasecmp($uc, $J["Engine"])) {
            $J["Engine"] = $uc;
            break;
        }
    }
    echo '
<form action="" method="post" id="form">
<p>
';
    if (support("columns") || $a == "") {
        echo 'Table name: <input name="name" data-maxlength="64" value="', h($J["name"]), '" autocapitalize="off">
';
        if ($a == "" && !$_POST) echo
        script("focus(qs('#form')['name']);");
        echo($vc ? "<select name='Engine'>" . optionlist(array("" => "(" . 'engine' . ")") + $vc, $J["Engine"]) . "</select>" . on_help("getTarget(event).value", 1) . script("qsl('select').onchange = helpClose;") : ""), ' ', ($pb && !preg_match("~sqlite|mssql~", $y) ? html_select("Collation", array("" => "(" . 'collation' . ")") + $pb, $J["Collation"]) : ""), ' <input type="submit" value="Save">
';
    }
    echo '
';
    if (support("columns")) {
        echo '<div class="scrollable">
<table cellspacing="0" id="edit-fields" class="nowrap">
';
        edit_fields($J["fields"], $pb, "TABLE", $ed);
        echo '</table>
</div>
<p>
Auto Increment: <input type="number" name="Auto_increment" size="6" value="', h($J["Auto_increment"]), '">
', checkbox("defaults", 1, ($_POST ? $_POST["defaults"] : adminer_setting("defaults")), 'Default values', "columnShow(this.checked, 5)", "jsonly"), (support("comment") ? checkbox("comments", 1, ($_POST ? $_POST["comments"] : adminer_setting("comments")), 'Comment', "editingCommentsClick(this, true);", "jsonly") . ' <input name="Comment" value="' . h($J["Comment"]) . '" data-maxlength="' . (min_version(5.5) ? 2048 : 60) . '">' : ''), '<p>
<input type="submit" value="Save">
';
    }
    echo '
';
    if ($a != "") {
        echo '<input type="submit" name="drop" value="Drop">', confirm(sprintf('Drop %s?', $a));
    }
    if (support("partitioning")) {
        $Tf = preg_match('~RANGE|LIST~', $J["partition_by"]);
        print_fieldset("partition", 'Partition by', $J["partition_by"]);
        echo '<p>
', "<select name='partition_by'>" . optionlist(array("" => "") + $Sf, $J["partition_by"]) . "</select>" . on_help("getTarget(event).value.replace(/./, 'PARTITION BY \$&')", 1) . script("qsl('select').onchange = partitionByChange;"), '(<input name="partition" value="', h($J["partition"]), '">)
Partitions: <input type="number" name="partitions" class="size', ($Tf || !$J["partition_by"] ? " hidden" : ""), '" value="', h($J["partitions"]), '">
<table cellspacing="0" id="partition-table"', ($Tf ? "" : " class='hidden'"), '>
<thead><tr><th>Partition name<th>Values</thead>
';
        foreach ($J["partition_names"] as $z => $X) {
            echo '<tr>', '<td><input name="partition_names[]" value="' . h($X) . '" autocapitalize="off">', ($z == count($J["partition_names"]) - 1 ? script("qsl('input').oninput = partitionNameChange;") : ''), '<td><input name="partition_values[]" value="' . h($J["partition_values"][$z]) . '">';
        }
        echo '</table>
</div></fieldset>
';
    }
    echo '<input type="hidden" name="token" value="', $pi, '">
</form>
', script("qs('#form')['defaults'].onclick();" . (support("comment") ? " editingCommentsClick(qs('#form')['comments']);" : ""));
} elseif (isset($_GET["indexes"])) {
    $a = $_GET["indexes"];
    $Jd = array("PRIMARY", "UNIQUE", "INDEX");
    $R = table_status($a, true);
    if (preg_match('~MyISAM|M?aria' . (min_version(5.6, '10.0.5') ? '|InnoDB' : '') . '~i', $R["Engine"])) $Jd[] = "FULLTEXT";
    if (preg_match('~MyISAM|M?aria' . (min_version(5.7, '10.2.2') ? '|InnoDB' : '') . '~i', $R["Engine"])) $Jd[] = "SPATIAL";
    $x = indexes($a);
    $kg = array();
    if ($y == "mongo") {
        $kg = $x["_id_"];
        unset($Jd[0]);
        unset($x["_id_"]);
    }
    $J = $_POST;
    if ($_POST && !$o && !$_POST["add"] && !$_POST["drop_col"]) {
        $c = array();
        foreach ($J["indexes"] as $w) {
            $C = $w["name"];
            if (in_array($w["type"], $Jd)) {
                $f = array();
                $ue = array();
                $Xb = array();
                $O = array();
                ksort($w["columns"]);
                foreach ($w["columns"] as $z => $e) {
                    if ($e != "") {
                        $te = $w["lengths"][$z];
                        $Wb = $w["descs"][$z];
                        $O[] = idf_escape($e) . ($te ? "(" . (+$te) . ")" : "") . ($Wb ? " DESC" : "");
                        $f[] = $e;
                        $ue[] = ($te ? $te : null);
                        $Xb[] = $Wb;
                    }
                }
                if ($f) {
                    $Dc = $x[$C];
                    if ($Dc) {
                        ksort($Dc["columns"]);
                        ksort($Dc["lengths"]);
                        ksort($Dc["descs"]);
                        if ($w["type"] == $Dc["type"] && array_values($Dc["columns"]) === $f && (!$Dc["lengths"] || array_values($Dc["lengths"]) === $ue) && array_values($Dc["descs"]) === $Xb) {
                            unset($x[$C]);
                            continue;
                        }
                    }
                    $c[] = array($w["type"], $C, $O);
                }
            }
        }
        foreach ($x
                 as $C => $Dc) $c[] = array($Dc["type"], $C, "DROP");
        if (!$c) redirect(ME . "table=" . urlencode($a));
        queries_redirect(ME . "table=" . urlencode($a), 'Indexes have been altered.', alter_indexes($a, $c));
    }
    page_header('Indexes', $o, array("table" => $a), h($a));
    $q = array_keys(fields($a));
    if ($_POST["add"]) {
        foreach ($J["indexes"] as $z => $w) {
            if ($w["columns"][count($w["columns"])] != "") $J["indexes"][$z]["columns"][] = "";
        }
        $w = end($J["indexes"]);
        if ($w["type"] || array_filter($w["columns"], 'strlen')) $J["indexes"][] = array("columns" => array(1 => ""));
    }
    if (!$J) {
        foreach ($x
                 as $z => $w) {
            $x[$z]["name"] = $z;
            $x[$z]["columns"][] = "";
        }
        $x[] = array("columns" => array(1 => ""));
        $J["indexes"] = $x;
    }
    echo '
<form action="" method="post">
<div class="scrollable">
<table cellspacing="0" class="nowrap">
<thead><tr>
<th id="label-type">Index Type
<th><input type="submit" class="wayoff">Column (length)
<th id="label-name">Name
<th><noscript>', "<input type='image' class='icon' name='add[0]' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=plus.gif&version=4.7.3") . "' alt='+' title='" . 'Add next' . "'>", '</noscript>
</thead>
';
    if ($kg) {
        echo "<tr><td>PRIMARY<td>";
        foreach ($kg["columns"] as $z => $e) {
            echo
            select_input(" disabled", $q, $e), "<label><input disabled type='checkbox'>" . 'descending' . "</label> ";
        }
        echo "<td><td>\n";
    }
    $ce = 1;
    foreach ($J["indexes"] as $w) {
        if (!$_POST["drop_col"] || $ce != key($_POST["drop_col"])) {
            echo "<tr><td>" . html_select("indexes[$ce][type]", array(-1 => "") + $Jd, $w["type"], ($ce == count($J["indexes"]) ? "indexesAddRow.call(this);" : 1), "label-type"), "<td>";
            ksort($w["columns"]);
            $t = 1;
            foreach ($w["columns"] as $z => $e) {
                echo "<span>" . select_input(" name='indexes[$ce][columns][$t]' title='" . 'Column' . "'", ($q ? array_combine($q, $q) : $q), $e, "partial(" . ($t == count($w["columns"]) ? "indexesAddColumn" : "indexesChangeColumn") . ", '" . js_escape($y == "sql" ? "" : $_GET["indexes"] . "_") . "')"), ($y == "sql" || $y == "mssql" ? "<input type='number' name='indexes[$ce][lengths][$t]' class='size' value='" . h($w["lengths"][$z]) . "' title='" . 'Length' . "'>" : ""), (support("descidx") ? checkbox("indexes[$ce][descs][$t]", 1, $w["descs"][$z], 'descending') : ""), " </span>";
                $t++;
            }
            echo "<td><input name='indexes[$ce][name]' value='" . h($w["name"]) . "' autocapitalize='off' aria-labelledby='label-name'>\n", "<td><input type='image' class='icon' name='drop_col[$ce]' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=cross.gif&version=4.7.3") . "' alt='x' title='" . 'Remove' . "'>" . script("qsl('input').onclick = partial(editingRemoveRow, 'indexes\$1[type]');");
        }
        $ce++;
    }
    echo '</table>
</div>
<p>
<input type="submit" value="Save">
<input type="hidden" name="token" value="', $pi, '">
</form>
';
} elseif (isset($_GET["database"])) {
    $J = $_POST;
    if ($_POST && !$o && !isset($_POST["add_x"])) {
        $C = trim($J["name"]);
        if ($_POST["drop"]) {
            $_GET["db"] = "";
            queries_redirect(remove_from_uri("db|database"), 'Database has been dropped.', drop_databases(array(DB)));
        } elseif (DB !== $C) {
            if (DB != "") {
                $_GET["db"] = $C;
                queries_redirect(preg_replace('~\bdb=[^&]*&~', '', ME) . "db=" . urlencode($C), 'Database has been renamed.', rename_database($C, $J["collation"]));
            } else {
                $l = explode("\n", str_replace("\r", "", $C));
                $Kh = true;
                $ne = "";
                foreach ($l
                         as $m) {
                    if (count($l) == 1 || $m != "") {
                        if (!create_database($m, $J["collation"])) $Kh = false;
                        $ne = $m;
                    }
                }
                restart_session();
                set_session("dbs", null);
                queries_redirect(ME . "db=" . urlencode($ne), 'Database has been created.', $Kh);
            }
        } else {
            if (!$J["collation"]) redirect(substr(ME, 0, -1));
            query_redirect("ALTER DATABASE " . idf_escape($C) . (preg_match('~^[a-z0-9_]+$~i', $J["collation"]) ? " COLLATE $J[collation]" : ""), substr(ME, 0, -1), 'Database has been altered.');
        }
    }
    page_header(DB != "" ? 'Alter database' : 'Create database', $o, array(), h(DB));
    $pb = collations();
    $C = DB;
    if ($_POST) $C = $J["name"]; elseif (DB != "") $J["collation"] = db_collation(DB, $pb);
    elseif ($y == "sql") {
        foreach (get_vals("SHOW GRANTS") as $ld) {
            if (preg_match('~ ON (`(([^\\\\`]|``|\\\\.)*)%`\.\*)?~', $ld, $B) && $B[1]) {
                $C = stripcslashes(idf_unescape("`$B[2]`"));
                break;
            }
        }
    }
    echo '
<form action="" method="post">
<p>
', ($_POST["add_x"] || strpos($C, "\n") ? '<textarea id="name" name="name" rows="10" cols="40">' . h($C) . '</textarea><br>' : '<input name="name" id="name" value="' . h($C) . '" data-maxlength="64" autocapitalize="off">') . "\n" . ($pb ? html_select("collation", array("" => "(" . 'collation' . ")") + $pb, $J["collation"]) . doc_link(array('sql' => "charset-charsets.html", 'mariadb' => "supported-character-sets-and-collations/", 'mssql' => "ms187963.aspx",)) : ""), script("focus(qs('#name'));"), '<input type="submit" value="Save">
';
    if (DB != "") echo "<input type='submit' name='drop' value='" . 'Drop' . "'>" . confirm(sprintf('Drop %s?', DB)) . "\n"; elseif (!$_POST["add_x"] && $_GET["db"] == "") echo "<input type='image' class='icon' name='add' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=plus.gif&version=4.7.3") . "' alt='+' title='" . 'Add next' . "'>\n";
    echo '<input type="hidden" name="token" value="', $pi, '">
</form>
';
} elseif (isset($_GET["scheme"])) {
    $J = $_POST;
    if ($_POST && !$o) {
        $A = preg_replace('~ns=[^&]*&~', '', ME) . "ns=";
        if ($_POST["drop"]) query_redirect("DROP SCHEMA " . idf_escape($_GET["ns"]), $A, 'Schema has been dropped.'); else {
            $C = trim($J["name"]);
            $A .= urlencode($C);
            if ($_GET["ns"] == "") query_redirect("CREATE SCHEMA " . idf_escape($C), $A, 'Schema has been created.'); elseif ($_GET["ns"] != $C) query_redirect("ALTER SCHEMA " . idf_escape($_GET["ns"]) . " RENAME TO " . idf_escape($C), $A, 'Schema has been altered.');
            else
                redirect($A);
        }
    }
    page_header($_GET["ns"] != "" ? 'Alter schema' : 'Create schema', $o);
    if (!$J) $J["name"] = $_GET["ns"];
    echo '
<form action="" method="post">
<p><input name="name" id="name" value="', h($J["name"]), '" autocapitalize="off">
', script("focus(qs('#name'));"), '<input type="submit" value="Save">
';
    if ($_GET["ns"] != "") echo "<input type='submit' name='drop' value='" . 'Drop' . "'>" . confirm(sprintf('Drop %s?', $_GET["ns"])) . "\n";
    echo '<input type="hidden" name="token" value="', $pi, '">
</form>
';
} elseif (isset($_GET["call"])) {
    $da = ($_GET["name"] ? $_GET["name"] : $_GET["call"]);
    page_header('Call' . ": " . h($da), $o);
    $Ug = routine($_GET["call"], (isset($_GET["callf"]) ? "FUNCTION" : "PROCEDURE"));
    $Hd = array();
    $Jf = array();
    foreach ($Ug["fields"] as $t => $p) {
        if (substr($p["inout"], -3) == "OUT") $Jf[$t] = "@" . idf_escape($p["field"]) . " AS " . idf_escape($p["field"]);
        if (!$p["inout"] || substr($p["inout"], 0, 2) == "IN") $Hd[] = $t;
    }
    if (!$o && $_POST) {
        $ab = array();
        foreach ($Ug["fields"] as $z => $p) {
            if (in_array($z, $Hd)) {
                $X = process_input($p);
                if ($X === false) $X = "''";
                if (isset($Jf[$z])) $g->query("SET @" . idf_escape($p["field"]) . " = $X");
            }
            $ab[] = (isset($Jf[$z]) ? "@" . idf_escape($p["field"]) : $X);
        }
        $G = (isset($_GET["callf"]) ? "SELECT" : "CALL") . " " . table($da) . "(" . implode(", ", $ab) . ")";
        $Dh = microtime(true);
        $H = $g->multi_query($G);
        $za = $g->affected_rows;
        echo $b->selectQuery($G, $Dh, !$H);
        if (!$H) echo "<p class='error'>" . error() . "\n"; else {
            $h = connect();
            if (is_object($h)) $h->select_db(DB);
            do {
                $H = $g->store_result();
                if (is_object($H)) select($H, $h); else
                    echo "<p class='message'>" . lang(array('Routine has been called, %d row affected.', 'Routine has been called, %d rows affected.'), $za) . "\n";
            } while ($g->next_result());
            if ($Jf) select($g->query("SELECT " . implode(", ", $Jf)));
        }
    }
    echo '
<form action="" method="post">
';
    if ($Hd) {
        echo "<table cellspacing='0' class='layout'>\n";
        foreach ($Hd
                 as $z) {
            $p = $Ug["fields"][$z];
            $C = $p["field"];
            echo "<tr><th>" . $b->fieldName($p);
            $Y = $_POST["fields"][$C];
            if ($Y != "") {
                if ($p["type"] == "enum") $Y = +$Y;
                if ($p["type"] == "set") $Y = array_sum($Y);
            }
            input($p, $Y, (string)$_POST["function"][$C]);
            echo "\n";
        }
        echo "</table>\n";
    }
    echo '<p>
<input type="submit" value="Call">
<input type="hidden" name="token" value="', $pi, '">
</form>
';
} elseif (isset($_GET["foreign"])) {
    $a = $_GET["foreign"];
    $C = $_GET["name"];
    $J = $_POST;
    if ($_POST && !$o && !$_POST["add"] && !$_POST["change"] && !$_POST["change-js"]) {
        $Le = ($_POST["drop"] ? 'Foreign key has been dropped.' : ($C != "" ? 'Foreign key has been altered.' : 'Foreign key has been created.'));
        $xe = ME . "table=" . urlencode($a);
        if (!$_POST["drop"]) {
            $J["source"] = array_filter($J["source"], 'strlen');
            ksort($J["source"]);
            $Yh = array();
            foreach ($J["source"] as $z => $X) $Yh[$z] = $J["target"][$z];
            $J["target"] = $Yh;
        }
        if ($y == "sqlite") queries_redirect($xe, $Le, recreate_table($a, $a, array(), array(), array(" $C" => ($_POST["drop"] ? "" : " " . format_foreign_key($J))))); else {
            $c = "ALTER TABLE " . table($a);
            $fc = "\nDROP " . ($y == "sql" ? "FOREIGN KEY " : "CONSTRAINT ") . idf_escape($C);
            if ($_POST["drop"]) query_redirect($c . $fc, $xe, $Le); else {
                query_redirect($c . ($C != "" ? "$fc," : "") . "\nADD" . format_foreign_key($J), $xe, $Le);
                $o = 'Source and target columns must have the same data type, there must be an index on the target columns and referenced data must exist.' . "<br>$o";
            }
        }
    }
    page_header('Foreign key', $o, array("table" => $a), h($a));
    if ($_POST) {
        ksort($J["source"]);
        if ($_POST["add"]) $J["source"][] = ""; elseif ($_POST["change"] || $_POST["change-js"]) $J["target"] = array();
    } elseif ($C != "") {
        $ed = foreign_keys($a);
        $J = $ed[$C];
        $J["source"][] = "";
    } else {
        $J["table"] = $a;
        $J["source"] = array("");
    }
    echo '
<form action="" method="post">
';
    $wh = array_keys(fields($a));
    if ($J["db"] != "") $g->select_db($J["db"]);
    if ($J["ns"] != "") set_schema($J["ns"]);
    $Dg = array_keys(array_filter(table_status('', true), 'fk_support'));
    $Yh = ($a === $J["table"] ? $wh : array_keys(fields(in_array($J["table"], $Dg) ? $J["table"] : reset($Dg))));
    $rf = "this.form['change-js'].value = '1'; this.form.submit();";
    echo "<p>" . 'Target table' . ": " . html_select("table", $Dg, $J["table"], $rf) . "\n";
    if ($y == "pgsql") echo 'Schema' . ": " . html_select("ns", $b->schemas(), $J["ns"] != "" ? $J["ns"] : $_GET["ns"], $rf); elseif ($y != "sqlite") {
        $Qb = array();
        foreach ($b->databases() as $m) {
            if (!information_schema($m)) $Qb[] = $m;
        }
        echo 'DB' . ": " . html_select("db", $Qb, $J["db"] != "" ? $J["db"] : $_GET["db"], $rf);
    }
    echo '<input type="hidden" name="change-js" value="">
<noscript><p><input type="submit" name="change" value="Change"></noscript>
<table cellspacing="0">
<thead><tr><th id="label-source">Source<th id="label-target">Target</thead>
';
    $ce = 0;
    foreach ($J["source"] as $z => $X) {
        echo "<tr>", "<td>" . html_select("source[" . (+$z) . "]", array(-1 => "") + $wh, $X, ($ce == count($J["source"]) - 1 ? "foreignAddRow.call(this);" : 1), "label-source"), "<td>" . html_select("target[" . (+$z) . "]", $Yh, $J["target"][$z], 1, "label-target");
        $ce++;
    }
    echo '</table>
<p>
ON DELETE: ', html_select("on_delete", array(-1 => "") + explode("|", $qf), $J["on_delete"]), ' ON UPDATE: ', html_select("on_update", array(-1 => "") + explode("|", $qf), $J["on_update"]), doc_link(array('sql' => "innodb-foreign-key-constraints.html", 'mariadb' => "foreign-keys/", 'pgsql' => "sql-createtable.html#SQL-CREATETABLE-REFERENCES", 'mssql' => "ms174979.aspx", 'oracle' => "clauses002.htm#sthref2903",)), '<p>
<input type="submit" value="Save">
<noscript><p><input type="submit" name="add" value="Add column"></noscript>
';
    if ($C != "") {
        echo '<input type="submit" name="drop" value="Drop">', confirm(sprintf('Drop %s?', $C));
    }
    echo '<input type="hidden" name="token" value="', $pi, '">
</form>
';
} elseif (isset($_GET["view"])) {
    $a = $_GET["view"];
    $J = $_POST;
    $Gf = "VIEW";
    if ($y == "pgsql" && $a != "") {
        $Fh = table_status($a);
        $Gf = strtoupper($Fh["Engine"]);
    }
    if ($_POST && !$o) {
        $C = trim($J["name"]);
        $Ga = " AS\n$J[select]";
        $xe = ME . "table=" . urlencode($C);
        $Le = 'View has been altered.';
        $T = ($_POST["materialized"] ? "MATERIALIZED VIEW" : "VIEW");
        if (!$_POST["drop"] && $a == $C && $y != "sqlite" && $T == "VIEW" && $Gf == "VIEW") query_redirect(($y == "mssql" ? "ALTER" : "CREATE OR REPLACE") . " VIEW " . table($C) . $Ga, $xe, $Le); else {
            $ai = $C . "_adminer_" . uniqid();
            drop_create("DROP $Gf " . table($a), "CREATE $T " . table($C) . $Ga, "DROP $T " . table($C), "CREATE $T " . table($ai) . $Ga, "DROP $T " . table($ai), ($_POST["drop"] ? substr(ME, 0, -1) : $xe), 'View has been dropped.', $Le, 'View has been created.', $a, $C);
        }
    }
    if (!$_POST && $a != "") {
        $J = view($a);
        $J["name"] = $a;
        $J["materialized"] = ($Gf != "VIEW");
        if (!$o) $o = error();
    }
    page_header(($a != "" ? 'Alter view' : 'Create view'), $o, array("table" => $a), h($a));
    echo '
<form action="" method="post">
<p>Name: <input name="name" value="', h($J["name"]), '" data-maxlength="64" autocapitalize="off">
', (support("materializedview") ? " " . checkbox("materialized", 1, $J["materialized"], 'Materialized view') : ""), '<p>';
    textarea("select", $J["select"]);
    echo '<p>
<input type="submit" value="Save">
';
    if ($a != "") {
        echo '<input type="submit" name="drop" value="Drop">', confirm(sprintf('Drop %s?', $a));
    }
    echo '<input type="hidden" name="token" value="', $pi, '">
</form>
';
} elseif (isset($_GET["event"])) {
    $aa = $_GET["event"];
    $Ud = array("YEAR", "QUARTER", "MONTH", "DAY", "HOUR", "MINUTE", "WEEK", "SECOND", "YEAR_MONTH", "DAY_HOUR", "DAY_MINUTE", "DAY_SECOND", "HOUR_MINUTE", "HOUR_SECOND", "MINUTE_SECOND");
    $Gh = array("ENABLED" => "ENABLE", "DISABLED" => "DISABLE", "SLAVESIDE_DISABLED" => "DISABLE ON SLAVE");
    $J = $_POST;
    if ($_POST && !$o) {
        if ($_POST["drop"]) query_redirect("DROP EVENT " . idf_escape($aa), substr(ME, 0, -1), 'Event has been dropped.'); elseif (in_array($J["INTERVAL_FIELD"], $Ud) && isset($Gh[$J["STATUS"]])) {
            $Zg = "\nON SCHEDULE " . ($J["INTERVAL_VALUE"] ? "EVERY " . q($J["INTERVAL_VALUE"]) . " $J[INTERVAL_FIELD]" . ($J["STARTS"] ? " STARTS " . q($J["STARTS"]) : "") . ($J["ENDS"] ? " ENDS " . q($J["ENDS"]) : "") : "AT " . q($J["STARTS"])) . " ON COMPLETION" . ($J["ON_COMPLETION"] ? "" : " NOT") . " PRESERVE";
            queries_redirect(substr(ME, 0, -1), ($aa != "" ? 'Event has been altered.' : 'Event has been created.'), queries(($aa != "" ? "ALTER EVENT " . idf_escape($aa) . $Zg . ($aa != $J["EVENT_NAME"] ? "\nRENAME TO " . idf_escape($J["EVENT_NAME"]) : "") : "CREATE EVENT " . idf_escape($J["EVENT_NAME"]) . $Zg) . "\n" . $Gh[$J["STATUS"]] . " COMMENT " . q($J["EVENT_COMMENT"]) . rtrim(" DO\n$J[EVENT_DEFINITION]", ";") . ";"));
        }
    }
    page_header(($aa != "" ? 'Alter event' . ": " . h($aa) : 'Create event'), $o);
    if (!$J && $aa != "") {
        $K = get_rows("SELECT * FROM information_schema.EVENTS WHERE EVENT_SCHEMA = " . q(DB) . " AND EVENT_NAME = " . q($aa));
        $J = reset($K);
    }
    echo '
<form action="" method="post">
<table cellspacing="0" class="layout">
<tr><th>Name<td><input name="EVENT_NAME" value="', h($J["EVENT_NAME"]), '" data-maxlength="64" autocapitalize="off">
<tr><th title="datetime">Start<td><input name="STARTS" value="', h("$J[EXECUTE_AT]$J[STARTS]"), '">
<tr><th title="datetime">End<td><input name="ENDS" value="', h($J["ENDS"]), '">
<tr><th>Every<td><input type="number" name="INTERVAL_VALUE" value="', h($J["INTERVAL_VALUE"]), '" class="size"> ', html_select("INTERVAL_FIELD", $Ud, $J["INTERVAL_FIELD"]), '<tr><th>Status<td>', html_select("STATUS", $Gh, $J["STATUS"]), '<tr><th>Comment<td><input name="EVENT_COMMENT" value="', h($J["EVENT_COMMENT"]), '" data-maxlength="64">
<tr><th><td>', checkbox("ON_COMPLETION", "PRESERVE", $J["ON_COMPLETION"] == "PRESERVE", 'On completion preserve'), '</table>
<p>';
    textarea("EVENT_DEFINITION", $J["EVENT_DEFINITION"]);
    echo '<p>
<input type="submit" value="Save">
';
    if ($aa != "") {
        echo '<input type="submit" name="drop" value="Drop">', confirm(sprintf('Drop %s?', $aa));
    }
    echo '<input type="hidden" name="token" value="', $pi, '">
</form>
';
} elseif (isset($_GET["procedure"])) {
    $da = ($_GET["name"] ? $_GET["name"] : $_GET["procedure"]);
    $Ug = (isset($_GET["function"]) ? "FUNCTION" : "PROCEDURE");
    $J = $_POST;
    $J["fields"] = (array)$J["fields"];
    if ($_POST && !process_fields($J["fields"]) && !$o) {
        $Df = routine($_GET["procedure"], $Ug);
        $ai = "$J[name]_adminer_" . uniqid();
        drop_create("DROP $Ug " . routine_id($da, $Df), create_routine($Ug, $J), "DROP $Ug " . routine_id($J["name"], $J), create_routine($Ug, array("name" => $ai) + $J), "DROP $Ug " . routine_id($ai, $J), substr(ME, 0, -1), 'Routine has been dropped.', 'Routine has been altered.', 'Routine has been created.', $da, $J["name"]);
    }
    page_header(($da != "" ? (isset($_GET["function"]) ? 'Alter function' : 'Alter procedure') . ": " . h($da) : (isset($_GET["function"]) ? 'Create function' : 'Create procedure')), $o);
    if (!$_POST && $da != "") {
        $J = routine($_GET["procedure"], $Ug);
        $J["name"] = $da;
    }
    $pb = get_vals("SHOW CHARACTER SET");
    sort($pb);
    $Vg = routine_languages();
    echo '
<form action="" method="post" id="form">
<p>Name: <input name="name" value="', h($J["name"]), '" data-maxlength="64" autocapitalize="off">
', ($Vg ? 'Language' . ": " . html_select("language", $Vg, $J["language"]) . "\n" : ""), '<input type="submit" value="Save">
<div class="scrollable">
<table cellspacing="0" class="nowrap">
';
    edit_fields($J["fields"], $pb, $Ug);
    if (isset($_GET["function"])) {
        echo "<tr><td>" . 'Return type';
        edit_type("returns", $J["returns"], $pb, array(), ($y == "pgsql" ? array("void", "trigger") : array()));
    }
    echo '</table>
</div>
<p>';
    textarea("definition", $J["definition"]);
    echo '<p>
<input type="submit" value="Save">
';
    if ($da != "") {
        echo '<input type="submit" name="drop" value="Drop">', confirm(sprintf('Drop %s?', $da));
    }
    echo '<input type="hidden" name="token" value="', $pi, '">
</form>
';
} elseif (isset($_GET["sequence"])) {
    $fa = $_GET["sequence"];
    $J = $_POST;
    if ($_POST && !$o) {
        $A = substr(ME, 0, -1);
        $C = trim($J["name"]);
        if ($_POST["drop"]) query_redirect("DROP SEQUENCE " . idf_escape($fa), $A, 'Sequence has been dropped.'); elseif ($fa == "") query_redirect("CREATE SEQUENCE " . idf_escape($C), $A, 'Sequence has been created.');
        elseif ($fa != $C) query_redirect("ALTER SEQUENCE " . idf_escape($fa) . " RENAME TO " . idf_escape($C), $A, 'Sequence has been altered.');
        else
            redirect($A);
    }
    page_header($fa != "" ? 'Alter sequence' . ": " . h($fa) : 'Create sequence', $o);
    if (!$J) $J["name"] = $fa;
    echo '
<form action="" method="post">
<p><input name="name" value="', h($J["name"]), '" autocapitalize="off">
<input type="submit" value="Save">
';
    if ($fa != "") echo "<input type='submit' name='drop' value='" . 'Drop' . "'>" . confirm(sprintf('Drop %s?', $fa)) . "\n";
    echo '<input type="hidden" name="token" value="', $pi, '">
</form>
';
} elseif (isset($_GET["type"])) {
    $ga = $_GET["type"];
    $J = $_POST;
    if ($_POST && !$o) {
        $A = substr(ME, 0, -1);
        if ($_POST["drop"]) query_redirect("DROP TYPE " . idf_escape($ga), $A, 'Type has been dropped.'); else
            query_redirect("CREATE TYPE " . idf_escape(trim($J["name"])) . " $J[as]", $A, 'Type has been created.');
    }
    page_header($ga != "" ? 'Alter type' . ": " . h($ga) : 'Create type', $o);
    if (!$J) $J["as"] = "AS ";
    echo '
<form action="" method="post">
<p>
';
    if ($ga != "") echo "<input type='submit' name='drop' value='" . 'Drop' . "'>" . confirm(sprintf('Drop %s?', $ga)) . "\n"; else {
        echo "<input name='name' value='" . h($J['name']) . "' autocapitalize='off'>\n";
        textarea("as", $J["as"]);
        echo "<p><input type='submit' value='" . 'Save' . "'>\n";
    }
    echo '<input type="hidden" name="token" value="', $pi, '">
</form>
';
} elseif (isset($_GET["trigger"])) {
    $a = $_GET["trigger"];
    $C = $_GET["name"];
    $_i = trigger_options();
    $J = (array)trigger($C) + array("Trigger" => $a . "_bi");
    if ($_POST) {
        if (!$o && in_array($_POST["Timing"], $_i["Timing"]) && in_array($_POST["Event"], $_i["Event"]) && in_array($_POST["Type"], $_i["Type"])) {
            $pf = " ON " . table($a);
            $fc = "DROP TRIGGER " . idf_escape($C) . ($y == "pgsql" ? $pf : "");
            $xe = ME . "table=" . urlencode($a);
            if ($_POST["drop"]) query_redirect($fc, $xe, 'Trigger has been dropped.'); else {
                if ($C != "") queries($fc);
                queries_redirect($xe, ($C != "" ? 'Trigger has been altered.' : 'Trigger has been created.'), queries(create_trigger($pf, $_POST)));
                if ($C != "") queries(create_trigger($pf, $J + array("Type" => reset($_i["Type"]))));
            }
        }
        $J = $_POST;
    }
    page_header(($C != "" ? 'Alter trigger' . ": " . h($C) : 'Create trigger'), $o, array("table" => $a));
    echo '
<form action="" method="post" id="form">
<table cellspacing="0" class="layout">
<tr><th>Time<td>', html_select("Timing", $_i["Timing"], $J["Timing"], "triggerChange(/^" . preg_quote($a, "/") . "_[ba][iud]$/, '" . js_escape($a) . "', this.form);"), '<tr><th>Event<td>', html_select("Event", $_i["Event"], $J["Event"], "this.form['Timing'].onchange();"), (in_array("UPDATE OF", $_i["Event"]) ? " <input name='Of' value='" . h($J["Of"]) . "' class='hidden'>" : ""), '<tr><th>Type<td>', html_select("Type", $_i["Type"], $J["Type"]), '</table>
<p>Name: <input name="Trigger" value="', h($J["Trigger"]), '" data-maxlength="64" autocapitalize="off">
', script("qs('#form')['Timing'].onchange();"), '<p>';
    textarea("Statement", $J["Statement"]);
    echo '<p>
<input type="submit" value="Save">
';
    if ($C != "") {
        echo '<input type="submit" name="drop" value="Drop">', confirm(sprintf('Drop %s?', $C));
    }
    echo '<input type="hidden" name="token" value="', $pi, '">
</form>
';
} elseif (isset($_GET["user"])) {
    $ha = $_GET["user"];
    $pg = array("" => array("All privileges" => ""));
    foreach (get_rows("SHOW PRIVILEGES") as $J) {
        foreach (explode(",", ($J["Privilege"] == "Grant option" ? "" : $J["Context"])) as $Bb) $pg[$Bb][$J["Privilege"]] = $J["Comment"];
    }
    $pg["Server Admin"] += $pg["File access on server"];
    $pg["Databases"]["Create routine"] = $pg["Procedures"]["Create routine"];
    unset($pg["Procedures"]["Create routine"]);
    $pg["Columns"] = array();
    foreach (array("Select", "Insert", "Update", "References") as $X) $pg["Columns"][$X] = $pg["Tables"][$X];
    unset($pg["Server Admin"]["Usage"]);
    foreach ($pg["Tables"] as $z => $X) unset($pg["Databases"][$z]);
    $Ye = array();
    if ($_POST) {
        foreach ($_POST["objects"] as $z => $X) $Ye[$X] = (array)$Ye[$X] + (array)$_POST["grants"][$z];
    }
    $md = array();
    $nf = "";
    if (isset($_GET["host"]) && ($H = $g->query("SHOW GRANTS FOR " . q($ha) . "@" . q($_GET["host"])))) {
        while ($J = $H->fetch_row()) {
            if (preg_match('~GRANT (.*) ON (.*) TO ~', $J[0], $B) && preg_match_all('~ *([^(,]*[^ ,(])( *\([^)]+\))?~', $B[1], $De, PREG_SET_ORDER)) {
                foreach ($De
                         as $X) {
                    if ($X[1] != "USAGE") $md["$B[2]$X[2]"][$X[1]] = true;
                    if (preg_match('~ WITH GRANT OPTION~', $J[0])) $md["$B[2]$X[2]"]["GRANT OPTION"] = true;
                }
            }
            if (preg_match("~ IDENTIFIED BY PASSWORD '([^']+)~", $J[0], $B)) $nf = $B[1];
        }
    }
    if ($_POST && !$o) {
        $of = (isset($_GET["host"]) ? q($ha) . "@" . q($_GET["host"]) : "''");
        if ($_POST["drop"]) query_redirect("DROP USER $of", ME . "privileges=", 'User has been dropped.'); else {
            $af = q($_POST["user"]) . "@" . q($_POST["host"]);
            $Xf = $_POST["pass"];
            if ($Xf != '' && !$_POST["hashed"] && !min_version(8)) {
                $Xf = $g->result("SELECT PASSWORD(" . q($Xf) . ")");
                $o = !$Xf;
            }
            $Gb = false;
            if (!$o) {
                if ($of != $af) {
                    $Gb = queries((min_version(5) ? "CREATE USER" : "GRANT USAGE ON *.* TO") . " $af IDENTIFIED BY " . (min_version(8) ? "" : "PASSWORD ") . q($Xf));
                    $o = !$Gb;
                } elseif ($Xf != $nf) queries("SET PASSWORD FOR $af = " . q($Xf));
            }
            if (!$o) {
                $Rg = array();
                foreach ($Ye
                         as $if => $ld) {
                    if (isset($_GET["grant"])) $ld = array_filter($ld);
                    $ld = array_keys($ld);
                    if (isset($_GET["grant"])) $Rg = array_diff(array_keys(array_filter($Ye[$if], 'strlen')), $ld); elseif ($of == $af) {
                        $lf = array_keys((array)$md[$if]);
                        $Rg = array_diff($lf, $ld);
                        $ld = array_diff($ld, $lf);
                        unset($md[$if]);
                    }
                    if (preg_match('~^(.+)\s*(\(.*\))?$~U', $if, $B) && (!grant("REVOKE", $Rg, $B[2], " ON $B[1] FROM $af") || !grant("GRANT", $ld, $B[2], " ON $B[1] TO $af"))) {
                        $o = true;
                        break;
                    }
                }
            }
            if (!$o && isset($_GET["host"])) {
                if ($of != $af) queries("DROP USER $of"); elseif (!isset($_GET["grant"])) {
                    foreach ($md
                             as $if => $Rg) {
                        if (preg_match('~^(.+)(\(.*\))?$~U', $if, $B)) grant("REVOKE", array_keys($Rg), $B[2], " ON $B[1] FROM $af");
                    }
                }
            }
            queries_redirect(ME . "privileges=", (isset($_GET["host"]) ? 'User has been altered.' : 'User has been created.'), !$o);
            if ($Gb) $g->query("DROP USER $af");
        }
    }
    page_header((isset($_GET["host"]) ? 'Username' . ": " . h("$ha@$_GET[host]") : 'Create user'), $o, array("privileges" => array('', 'Privileges')));
    if ($_POST) {
        $J = $_POST;
        $md = $Ye;
    } else {
        $J = $_GET + array("host" => $g->result("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', -1)"));
        $J["pass"] = $nf;
        if ($nf != "") $J["hashed"] = true;
        $md[(DB == "" || $md ? "" : idf_escape(addcslashes(DB, "%_\\"))) . ".*"] = array();
    }
    echo '<form action="" method="post">
<table cellspacing="0" class="layout">
<tr><th>Server<td><input name="host" data-maxlength="60" value="', h($J["host"]), '" autocapitalize="off">
<tr><th>Username<td><input name="user" data-maxlength="80" value="', h($J["user"]), '" autocapitalize="off">
<tr><th>Password<td><input name="pass" id="pass" value="', h($J["pass"]), '" autocomplete="new-password">
';
    if (!$J["hashed"]) echo
    script("typePassword(qs('#pass'));");
    echo(min_version(8) ? "" : checkbox("hashed", 1, $J["hashed"], 'Hashed', "typePassword(this.form['pass'], this.checked);")), '</table>

';
    echo "<table cellspacing='0'>\n", "<thead><tr><th colspan='2'>" . 'Privileges' . doc_link(array('sql' => "grant.html#priv_level"));
    $t = 0;
    foreach ($md
             as $if => $ld) {
        echo '<th>' . ($if != "*.*" ? "<input name='objects[$t]' value='" . h($if) . "' size='10' autocapitalize='off'>" : "<input type='hidden' name='objects[$t]' value='*.*' size='10'>*.*");
        $t++;
    }
    echo "</thead>\n";
    foreach (array("" => "", "Server Admin" => 'Server', "Databases" => 'Database', "Tables" => 'Table', "Columns" => 'Column', "Procedures" => 'Routine',) as $Bb => $Wb) {
        foreach ((array)$pg[$Bb] as $og => $ub) {
            echo "<tr" . odd() . "><td" . ($Wb ? ">$Wb<td" : " colspan='2'") . ' lang="en" title="' . h($ub) . '">' . h($og);
            $t = 0;
            foreach ($md
                     as $if => $ld) {
                $C = "'grants[$t][" . h(strtoupper($og)) . "]'";
                $Y = $ld[strtoupper($og)];
                if ($Bb == "Server Admin" && $if != (isset($md["*.*"]) ? "*.*" : ".*")) echo "<td>"; elseif (isset($_GET["grant"])) echo "<td><select name=$C><option><option value='1'" . ($Y ? " selected" : "") . ">" . 'Grant' . "<option value='0'" . ($Y == "0" ? " selected" : "") . ">" . 'Revoke' . "</select>";
                else {
                    echo "<td align='center'><label class='block'>", "<input type='checkbox' name=$C value='1'" . ($Y ? " checked" : "") . ($og == "All privileges" ? " id='grants-$t-all'>" : ">" . ($og == "Grant option" ? "" : script("qsl('input').onclick = function () { if (this.checked) formUncheck('grants-$t-all'); };"))), "</label>";
                }
                $t++;
            }
        }
    }
    echo "</table>\n", '<p>
<input type="submit" value="Save">
';
    if (isset($_GET["host"])) {
        echo '<input type="submit" name="drop" value="Drop">', confirm(sprintf('Drop %s?', "$ha@$_GET[host]"));
    }
    echo '<input type="hidden" name="token" value="', $pi, '">
</form>
';
} elseif (isset($_GET["processlist"])) {
    if (support("kill") && $_POST && !$o) {
        $je = 0;
        foreach ((array)$_POST["kill"] as $X) {
            if (kill_process($X)) $je++;
        }
        queries_redirect(ME . "processlist=", lang(array('%d process has been killed.', '%d processes have been killed.'), $je), $je || !$_POST["kill"]);
    }
    page_header('Process list', $o);
    echo '
<form action="" method="post">
<div class="scrollable">
<table cellspacing="0" class="nowrap checkable">
', script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});");
    $t = -1;
    foreach (process_list() as $t => $J) {
        if (!$t) {
            echo "<thead><tr lang='en'>" . (support("kill") ? "<th>" : "");
            foreach ($J
                     as $z => $X) echo "<th>$z" . doc_link(array('sql' => "show-processlist.html#processlist_" . strtolower($z), 'pgsql' => "monitoring-stats.html#PG-STAT-ACTIVITY-VIEW", 'oracle' => "../b14237/dynviews_2088.htm",));
            echo "</thead>\n";
        }
        echo "<tr" . odd() . ">" . (support("kill") ? "<td>" . checkbox("kill[]", $J[$y == "sql" ? "Id" : "pid"], 0) : "");
        foreach ($J
                 as $z => $X) echo "<td>" . (($y == "sql" && $z == "Info" && preg_match("~Query|Killed~", $J["Command"]) && $X != "") || ($y == "pgsql" && $z == "current_query" && $X != "<IDLE>") || ($y == "oracle" && $z == "sql_text" && $X != "") ? "<code class='jush-$y'>" . shorten_utf8($X, 100, "</code>") . ' <a href="' . h(ME . ($J["db"] != "" ? "db=" . urlencode($J["db"]) . "&" : "") . "sql=" . urlencode($X)) . '">' . 'Clone' . '</a>' : h($X));
        echo "\n";
    }
    echo '</table>
</div>
<p>
';
    if (support("kill")) {
        echo ($t + 1) . "/" . sprintf('%d in total', max_connections()), "<p><input type='submit' value='" . 'Kill' . "'>\n";
    }
    echo '<input type="hidden" name="token" value="', $pi, '">
</form>
', script("tableCheck();");
} elseif (isset($_GET["select"])) {
    $a = $_GET["select"];
    $R = table_status1($a);
    $x = indexes($a);
    $q = fields($a);
    $ed = column_foreign_keys($a);
    $kf = $R["Oid"];
    parse_str($_COOKIE["adminer_import"], $ya);
    $Sg = array();
    $f = array();
    $ei = null;
    foreach ($q
             as $z => $p) {
        $C = $b->fieldName($p);
        if (isset($p["privileges"]["select"]) && $C != "") {
            $f[$z] = html_entity_decode(strip_tags($C), ENT_QUOTES);
            if (is_shortable($p)) $ei = $b->selectLengthProcess();
        }
        $Sg += $p["privileges"];
    }
    list($L, $nd) = $b->selectColumnsProcess($f, $x);
    $Yd = count($nd) < count($L);
    $Z = $b->selectSearchProcess($q, $x);
    $_f = $b->selectOrderProcess($q, $x);
    $_ = $b->selectLimitProcess();
    if ($_GET["val"] && is_ajax()) {
        header("Content-Type: text/plain; charset=utf-8");
        foreach ($_GET["val"] as $Gi => $J) {
            $Ga = convert_field($q[key($J)]);
            $L = array($Ga ? $Ga : idf_escape(key($J)));
            $Z[] = where_check($Gi, $q);
            $I = $n->select($a, $L, $Z, $L);
            if ($I) echo
            reset($I->fetch_row());
        }
        exit;
    }
    $kg = $Ii = null;
    foreach ($x
             as $w) {
        if ($w["type"] == "PRIMARY") {
            $kg = array_flip($w["columns"]);
            $Ii = ($L ? $kg : array());
            foreach ($Ii
                     as $z => $X) {
                if (in_array(idf_escape($z), $L)) unset($Ii[$z]);
            }
            break;
        }
    }
    if ($kf && !$kg) {
        $kg = $Ii = array($kf => 0);
        $x[] = array("type" => "PRIMARY", "columns" => array($kf));
    }
    if ($_POST && !$o) {
        $kj = $Z;
        if (!$_POST["all"] && is_array($_POST["check"])) {
            $gb = array();
            foreach ($_POST["check"] as $db) $gb[] = where_check($db, $q);
            $kj[] = "((" . implode(") OR (", $gb) . "))";
        }
        $kj = ($kj ? "\nWHERE " . implode(" AND ", $kj) : "");
        if ($_POST["export"]) {
            cookie("adminer_import", "output=" . urlencode($_POST["output"]) . "&format=" . urlencode($_POST["format"]));
            dump_headers($a);
            $b->dumpTable($a, "");
            $jd = ($L ? implode(", ", $L) : "*") . convert_fields($f, $q, $L) . "\nFROM " . table($a);
            $pd = ($nd && $Yd ? "\nGROUP BY " . implode(", ", $nd) : "") . ($_f ? "\nORDER BY " . implode(", ", $_f) : "");
            if (!is_array($_POST["check"]) || $kg) $G = "SELECT $jd$kj$pd"; else {
                $Ei = array();
                foreach ($_POST["check"] as $X) $Ei[] = "(SELECT" . limit($jd, "\nWHERE " . ($Z ? implode(" AND ", $Z) . " AND " : "") . where_check($X, $q) . $pd, 1) . ")";
                $G = implode(" UNION ALL ", $Ei);
            }
            $b->dumpData($a, "table", $G);
            exit;
        }
        if (!$b->selectEmailProcess($Z, $ed)) {
            if ($_POST["save"] || $_POST["delete"]) {
                $H = true;
                $za = 0;
                $O = array();
                if (!$_POST["delete"]) {
                    foreach ($f
                             as $C => $X) {
                        $X = process_input($q[$C]);
                        if ($X !== null && ($_POST["clone"] || $X !== false)) $O[idf_escape($C)] = ($X !== false ? $X : idf_escape($C));
                    }
                }
                if ($_POST["delete"] || $O) {
                    if ($_POST["clone"]) $G = "INTO " . table($a) . " (" . implode(", ", array_keys($O)) . ")\nSELECT " . implode(", ", $O) . "\nFROM " . table($a);
                    if ($_POST["all"] || ($kg && is_array($_POST["check"])) || $Yd) {
                        $H = ($_POST["delete"] ? $n->delete($a, $kj) : ($_POST["clone"] ? queries("INSERT $G$kj") : $n->update($a, $O, $kj)));
                        $za = $g->affected_rows;
                    } else {
                        foreach ((array)$_POST["check"] as $X) {
                            $gj = "\nWHERE " . ($Z ? implode(" AND ", $Z) . " AND " : "") . where_check($X, $q);
                            $H = ($_POST["delete"] ? $n->delete($a, $gj, 1) : ($_POST["clone"] ? queries("INSERT" . limit1($a, $G, $gj)) : $n->update($a, $O, $gj, 1)));
                            if (!$H) break;
                            $za += $g->affected_rows;
                        }
                    }
                }
                $Le = lang(array('%d item has been affected.', '%d items have been affected.'), $za);
                if ($_POST["clone"] && $H && $za == 1) {
                    $oe = last_id();
                    if ($oe) $Le = sprintf('Item%s has been inserted.', " $oe");
                }
                queries_redirect(remove_from_uri($_POST["all"] && $_POST["delete"] ? "page" : ""), $Le, $H);
                if (!$_POST["delete"]) {
                    edit_form($a, $q, (array)$_POST["fields"], !$_POST["clone"]);
                    page_footer();
                    exit;
                }
            } elseif (!$_POST["import"]) {
                if (!$_POST["val"]) $o = 'Ctrl+click on a value to modify it.'; else {
                    $H = true;
                    $za = 0;
                    foreach ($_POST["val"] as $Gi => $J) {
                        $O = array();
                        foreach ($J
                                 as $z => $X) {
                            $z = bracket_escape($z, 1);
                            $O[idf_escape($z)] = (preg_match('~char|text~', $q[$z]["type"]) || $X != "" ? $b->processInput($q[$z], $X) : "NULL");
                        }
                        $H = $n->update($a, $O, " WHERE " . ($Z ? implode(" AND ", $Z) . " AND " : "") . where_check($Gi, $q), !$Yd && !$kg, " ");
                        if (!$H) break;
                        $za += $g->affected_rows;
                    }
                    queries_redirect(remove_from_uri(), lang(array('%d item has been affected.', '%d items have been affected.'), $za), $H);
                }
            } elseif (!is_string($Tc = get_file("csv_file", true))) $o = upload_error($Tc);
            elseif (!preg_match('~~u', $Tc)) $o = 'File must be in UTF-8 encoding.';
            else {
                cookie("adminer_import", "output=" . urlencode($ya["output"]) . "&format=" . urlencode($_POST["separator"]));
                $H = true;
                $rb = array_keys($q);
                preg_match_all('~(?>"[^"]*"|[^"\r\n]+)+~', $Tc, $De);
                $za = count($De[0]);
                $n->begin();
                $M = ($_POST["separator"] == "csv" ? "," : ($_POST["separator"] == "tsv" ? "\t" : ";"));
                $K = array();
                foreach ($De[0] as $z => $X) {
                    preg_match_all("~((?>\"[^\"]*\")+|[^$M]*)$M~", $X . $M, $Ee);
                    if (!$z && !array_diff($Ee[1], $rb)) {
                        $rb = $Ee[1];
                        $za--;
                    } else {
                        $O = array();
                        foreach ($Ee[1] as $t => $nb) $O[idf_escape($rb[$t])] = ($nb == "" && $q[$rb[$t]]["null"] ? "NULL" : q(str_replace('""', '"', preg_replace('~^"|"$~', '', $nb))));
                        $K[] = $O;
                    }
                }
                $H = (!$K || $n->insertUpdate($a, $K, $kg));
                if ($H) $H = $n->commit();
                queries_redirect(remove_from_uri("page"), lang(array('%d row has been imported.', '%d rows have been imported.'), $za), $H);
                $n->rollback();
            }
        }
    }
    $Qh = $b->tableName($R);
    if (is_ajax()) {
        page_headers();
        ob_start();
    } else
        page_header('Select' . ": $Qh", $o);
    $O = null;
    if (isset($Sg["insert"]) || !support("table")) {
        $O = "";
        foreach ((array)$_GET["where"] as $X) {
            if ($ed[$X["col"]] && count($ed[$X["col"]]) == 1 && ($X["op"] == "=" || (!$X["op"] && !preg_match('~[_%]~', $X["val"])))) $O .= "&set" . urlencode("[" . bracket_escape($X["col"]) . "]") . "=" . urlencode($X["val"]);
        }
    }
    $b->selectLinks($R, $O);
    if (!$f && support("table")) echo "<p class='error'>" . 'Unable to select the table' . ($q ? "." : ": " . error()) . "\n"; else {
        echo "<form action='' id='form'>\n", "<div style='display: none;'>";
        hidden_fields_get();
        echo(DB != "" ? '<input type="hidden" name="db" value="' . h(DB) . '">' . (isset($_GET["ns"]) ? '<input type="hidden" name="ns" value="' . h($_GET["ns"]) . '">' : "") : "");
        echo '<input type="hidden" name="select" value="' . h($a) . '">', "</div>\n";
        $b->selectColumnsPrint($L, $f);
        $b->selectSearchPrint($Z, $f, $x);
        $b->selectOrderPrint($_f, $f, $x);
        $b->selectLimitPrint($_);
        $b->selectLengthPrint($ei);
        $b->selectActionPrint($x);
        echo "</form>\n";
        $E = $_GET["page"];
        if ($E == "last") {
            $hd = $g->result(count_rows($a, $Z, $Yd, $nd));
            $E = floor(max(0, $hd - 1) / $_);
        }
        $eh = $L;
        $od = $nd;
        if (!$eh) {
            $eh[] = "*";
            $Cb = convert_fields($f, $q, $L);
            if ($Cb) $eh[] = substr($Cb, 2);
        }
        foreach ($L
                 as $z => $X) {
            $p = $q[idf_unescape($X)];
            if ($p && ($Ga = convert_field($p))) $eh[$z] = "$Ga AS $X";
        }
        if (!$Yd && $Ii) {
            foreach ($Ii
                     as $z => $X) {
                $eh[] = idf_escape($z);
                if ($od) $od[] = idf_escape($z);
            }
        }
        $H = $n->select($a, $eh, $Z, $od, $_f, $_, $E, true);
        if (!$H) echo "<p class='error'>" . error() . "\n"; else {
            if ($y == "mssql" && $E) $H->seek($_ * $E);
            $sc = array();
            echo "<form action='' method='post' enctype='multipart/form-data'>\n";
            $K = array();
            while ($J = $H->fetch_assoc()) {
                if ($E && $y == "oracle") unset($J["RNUM"]);
                $K[] = $J;
            }
            if ($_GET["page"] != "last" && $_ != "" && $nd && $Yd && $y == "sql") $hd = $g->result(" SELECT FOUND_ROWS()");
            if (!$K) echo "<p class='message'>" . 'No rows.' . "\n"; else {
                $Qa = $b->backwardKeys($a, $Qh);
                echo "<div class='scrollable'>", "<table id='table' cellspacing='0' class='nowrap checkable'>", script("mixin(qs('#table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true), onkeydown: editingKeydown});"), "<thead><tr>" . (!$nd && $L ? "" : "<td><input type='checkbox' id='all-page' class='jsonly'>" . script("qs('#all-page').onclick = partial(formCheck, /check/);", "") . " <a href='" . h($_GET["modify"] ? remove_from_uri("modify") : $_SERVER["REQUEST_URI"] . "&modify=1") . "'>" . 'Modify' . "</a>");
                $Xe = array();
                $kd = array();
                reset($L);
                $zg = 1;
                foreach ($K[0] as $z => $X) {
                    if (!isset($Ii[$z])) {
                        $X = $_GET["columns"][key($L)];
                        $p = $q[$L ? ($X ? $X["col"] : current($L)) : $z];
                        $C = ($p ? $b->fieldName($p, $zg) : ($X["fun"] ? "*" : $z));
                        if ($C != "") {
                            $zg++;
                            $Xe[$z] = $C;
                            $e = idf_escape($z);
                            $Bd = remove_from_uri('(order|desc)[^=]*|page') . '&order%5B0%5D=' . urlencode($z);
                            $Wb = "&desc%5B0%5D=1";
                            echo "<th>" . script("mixin(qsl('th'), {onmouseover: partial(columnMouse), onmouseout: partial(columnMouse, ' hidden')});", ""), '<a href="' . h($Bd . ($_f[0] == $e || $_f[0] == $z || (!$_f && $Yd && $nd[0] == $e) ? $Wb : '')) . '">';
                            echo
                                apply_sql_function($X["fun"], $C) . "</a>";
                            echo "<span class='column hidden'>", "<a href='" . h($Bd . $Wb) . "' title='" . 'descending' . "' class='text'> ↓</a>";
                            if (!$X["fun"]) {
                                echo '<a href="#fieldset-search" title="' . 'Search' . '" class="text jsonly"> =</a>', script("qsl('a').onclick = partial(selectSearch, '" . js_escape($z) . "');");
                            }
                            echo "</span>";
                        }
                        $kd[$z] = $X["fun"];
                        next($L);
                    }
                }
                $ue = array();
                if ($_GET["modify"]) {
                    foreach ($K
                             as $J) {
                        foreach ($J
                                 as $z => $X) $ue[$z] = max($ue[$z], min(40, strlen(utf8_decode($X))));
                    }
                }
                echo ($Qa ? "<th>" . 'Relations' : "") . "</thead>\n";
                if (is_ajax()) {
                    if ($_ % 2 == 1 && $E % 2 == 1) odd();
                    ob_end_clean();
                }
                foreach ($b->rowDescriptions($K, $ed) as $We => $J) {
                    $Fi = unique_array($K[$We], $x);
                    if (!$Fi) {
                        $Fi = array();
                        foreach ($K[$We] as $z => $X) {
                            if (!preg_match('~^(COUNT\((\*|(DISTINCT )?`(?:[^`]|``)+`)\)|(AVG|GROUP_CONCAT|MAX|MIN|SUM)\(`(?:[^`]|``)+`\))$~', $z)) $Fi[$z] = $X;
                        }
                    }
                    $Gi = "";
                    foreach ($Fi
                             as $z => $X) {
                        if (($y == "sql" || $y == "pgsql") && preg_match('~char|text|enum|set~', $q[$z]["type"]) && strlen($X) > 64) {
                            $z = (strpos($z, '(') ? $z : idf_escape($z));
                            $z = "MD5(" . ($y != 'sql' || preg_match("~^utf8~", $q[$z]["collation"]) ? $z : "CONVERT($z USING " . charset($g) . ")") . ")";
                            $X = md5($X);
                        }
                        $Gi .= "&" . ($X !== null ? urlencode("where[" . bracket_escape($z) . "]") . "=" . urlencode($X) : "null%5B%5D=" . urlencode($z));
                    }
                    echo "<tr" . odd() . ">" . (!$nd && $L ? "" : "<td>" . checkbox("check[]", substr($Gi, 1), in_array(substr($Gi, 1), (array)$_POST["check"])) . ($Yd || information_schema(DB) ? "" : " <a href='" . h(ME . "edit=" . urlencode($a) . $Gi) . "' class='edit'>" . 'edit' . "</a>"));
                    foreach ($J
                             as $z => $X) {
                        if (isset($Xe[$z])) {
                            $p = $q[$z];
                            $X = $n->value($X, $p);
                            if ($X != "" && (!isset($sc[$z]) || $sc[$z] != "")) $sc[$z] = (is_mail($X) ? $Xe[$z] : "");
                            $A = "";
                            if (preg_match('~blob|bytea|raw|file~', $p["type"]) && $X != "") $A = ME . 'download=' . urlencode($a) . '&field=' . urlencode($z) . $Gi;
                            if (!$A && $X !== null) {
                                foreach ((array)$ed[$z] as $r) {
                                    if (count($ed[$z]) == 1 || end($r["source"]) == $z) {
                                        $A = "";
                                        foreach ($r["source"] as $t => $wh) $A .= where_link($t, $r["target"][$t], $K[$We][$wh]);
                                        $A = ($r["db"] != "" ? preg_replace('~([?&]db=)[^&]+~', '\1' . urlencode($r["db"]), ME) : ME) . 'select=' . urlencode($r["table"]) . $A;
                                        if ($r["ns"]) $A = preg_replace('~([?&]ns=)[^&]+~', '\1' . urlencode($r["ns"]), $A);
                                        if (count($r["source"]) == 1) break;
                                    }
                                }
                            }
                            if ($z == "COUNT(*)") {
                                $A = ME . "select=" . urlencode($a);
                                $t = 0;
                                foreach ((array)$_GET["where"] as $W) {
                                    if (!array_key_exists($W["col"], $Fi)) $A .= where_link($t++, $W["col"], $W["val"], $W["op"]);
                                }
                                foreach ($Fi
                                         as $de => $W) $A .= where_link($t++, $de, $W);
                            }
                            $X = select_value($X, $A, $p, $ei);
                            $u = h("val[$Gi][" . bracket_escape($z) . "]");
                            $Y = $_POST["val"][$Gi][bracket_escape($z)];
                            $nc = !is_array($J[$z]) && is_utf8($X) && $K[$We][$z] == $J[$z] && !$kd[$z];
                            $di = preg_match('~text|lob~', $p["type"]);
                            if (($_GET["modify"] && $nc) || $Y !== null) {
                                $sd = h($Y !== null ? $Y : $J[$z]);
                                echo "<td>" . ($di ? "<textarea name='$u' cols='30' rows='" . (substr_count($J[$z], "\n") + 1) . "'>$sd</textarea>" : "<input name='$u' value='$sd' size='$ue[$z]'>");
                            } else {
                                $ze = strpos($X, "<i>…</i>");
                                echo "<td id='$u' data-text='" . ($ze ? 2 : ($di ? 1 : 0)) . "'" . ($nc ? "" : " data-warning='" . h('Use edit link to modify this value.') . "'") . ">$X</td>";
                            }
                        }
                    }
                    if ($Qa) echo "<td>";
                    $b->backwardKeysPrint($Qa, $K[$We]);
                    echo "</tr>\n";
                }
                if (is_ajax()) exit;
                echo "</table>\n", "</div>\n";
            }
            if (!is_ajax()) {
                if ($K || $E) {
                    $Bc = true;
                    if ($_GET["page"] != "last") {
                        if ($_ == "" || (count($K) < $_ && ($K || !$E))) $hd = ($E ? $E * $_ : 0) + count($K); elseif ($y != "sql" || !$Yd) {
                            $hd = ($Yd ? false : found_rows($R, $Z));
                            if ($hd < max(1e4, 2 * ($E + 1) * $_)) $hd = reset(slow_query(count_rows($a, $Z, $Yd, $nd))); else$Bc = false;
                        }
                    }
                    $Mf = ($_ != "" && ($hd === false || $hd > $_ || $E));
                    if ($Mf) {
                        echo(($hd === false ? count($K) + 1 : $hd - $E * $_) > $_ ? '<p><a href="' . h(remove_from_uri("page") . "&page=" . ($E + 1)) . '" class="loadmore">' . 'Load more data' . '</a>' . script("qsl('a').onclick = partial(selectLoadMore, " . (+$_) . ", '" . 'Loading' . "…');", "") : ''), "\n";
                    }
                }
                echo "<div class='footer'><div>\n";
                if ($K || $E) {
                    if ($Mf) {
                        $Ge = ($hd === false ? $E + (count($K) >= $_ ? 2 : 1) : floor(($hd - 1) / $_));
                        echo "<fieldset>";
                        if ($y != "simpledb") {
                            echo "<legend><a href='" . h(remove_from_uri("page")) . "'>" . 'Page' . "</a></legend>", script("qsl('a').onclick = function () { pageClick(this.href, +prompt('" . 'Page' . "', '" . ($E + 1) . "')); return false; };"), pagination(0, $E) . ($E > 5 ? " …" : "");
                            for ($t = max(1, $E - 4); $t < min($Ge, $E + 5); $t++) echo
                            pagination($t, $E);
                            if ($Ge > 0) {
                                echo($E + 5 < $Ge ? " …" : ""), ($Bc && $hd !== false ? pagination($Ge, $E) : " <a href='" . h(remove_from_uri("page") . "&page=last") . "' title='~$Ge'>" . 'last' . "</a>");
                            }
                        } else {
                            echo "<legend>" . 'Page' . "</legend>", pagination(0, $E) . ($E > 1 ? " …" : ""), ($E ? pagination($E, $E) : ""), ($Ge > $E ? pagination($E + 1, $E) . ($Ge > $E + 1 ? " …" : "") : "");
                        }
                        echo "</fieldset>\n";
                    }
                    echo "<fieldset>", "<legend>" . 'Whole result' . "</legend>";
                    $bc = ($Bc ? "" : "~ ") . $hd;
                    echo
                        checkbox("all", 1, 0, ($hd !== false ? ($Bc ? "" : "~ ") . lang(array('%d row', '%d rows'), $hd) : ""), "var checked = formChecked(this, /check/); selectCount('selected', this.checked ? '$bc' : checked); selectCount('selected2', this.checked || !checked ? '$bc' : checked);") . "\n", "</fieldset>\n";
                    if ($b->selectCommandPrint()) {
                        echo '<fieldset', ($_GET["modify"] ? '' : ' class="jsonly"'), '><legend>Modify</legend><div>
<input type="submit" value="Save"', ($_GET["modify"] ? '' : ' title="' . 'Ctrl+click on a value to modify it.' . '"'), '>
</div></fieldset>
<fieldset><legend>Selected <span id="selected"></span></legend><div>
<input type="submit" name="edit" value="Edit">
<input type="submit" name="clone" value="Clone">
<input type="submit" name="delete" value="Delete">', confirm(), '</div></fieldset>
';
                    }
                    $fd = $b->dumpFormat();
                    foreach ((array)$_GET["columns"] as $e) {
                        if ($e["fun"]) {
                            unset($fd['sql']);
                            break;
                        }
                    }
                    if ($fd) {
                        print_fieldset("export", 'Export' . " <span id='selected2'></span>");
                        $Kf = $b->dumpOutput();
                        echo($Kf ? html_select("output", $Kf, $ya["output"]) . " " : ""), html_select("format", $fd, $ya["format"]), " <input type='submit' name='export' value='" . 'Export' . "'>\n", "</div></fieldset>\n";
                    }
                    $b->selectEmailPrint(array_filter($sc, 'strlen'), $f);
                }
                echo "</div></div>\n";
                if ($b->selectImportPrint()) {
                    echo "<div>", "<a href='#import'>" . 'Import' . "</a>", script("qsl('a').onclick = partial(toggle, 'import');", ""), "<span id='import' class='hidden'>: ", "<input type='file' name='csv_file'> ", html_select("separator", array("csv" => "CSV,", "csv;" => "CSV;", "tsv" => "TSV"), $ya["format"], 1);
                    echo " <input type='submit' name='import' value='" . 'Import' . "'>", "</span>", "</div>";
                }
                echo "<input type='hidden' name='token' value='$pi'>\n", "</form>\n", (!$nd && $L ? "" : script("tableCheck();"));
            }
        }
    }
    if (is_ajax()) {
        ob_end_clean();
        exit;
    }
} elseif (isset($_GET["variables"])) {
    $Fh = isset($_GET["status"]);
    page_header($Fh ? 'Status' : 'Variables');
    $Xi = ($Fh ? show_status() : show_variables());
    if (!$Xi) echo "<p class='message'>" . 'No rows.' . "\n"; else {
        echo "<table cellspacing='0'>\n";
        foreach ($Xi
                 as $z => $X) {
            echo "<tr>", "<th><code class='jush-" . $y . ($Fh ? "status" : "set") . "'>" . h($z) . "</code>", "<td>" . h($X);
        }
        echo "</table>\n";
    }
} elseif (isset($_GET["script"])) {
    header("Content-Type: text/javascript; charset=utf-8");
    if ($_GET["script"] == "db") {
        $Nh = array("Data_length" => 0, "Index_length" => 0, "Data_free" => 0);
        foreach (table_status() as $C => $R) {
            json_row("Comment-$C", h($R["Comment"]));
            if (!is_view($R)) {
                foreach (array("Engine", "Collation") as $z) json_row("$z-$C", h($R[$z]));
                foreach ($Nh + array("Auto_increment" => 0, "Rows" => 0) as $z => $X) {
                    if ($R[$z] != "") {
                        $X = format_number($R[$z]);
                        json_row("$z-$C", ($z == "Rows" && $X && $R["Engine"] == ($zh == "pgsql" ? "table" : "InnoDB") ? "~ $X" : $X));
                        if (isset($Nh[$z])) $Nh[$z] += ($R["Engine"] != "InnoDB" || $z != "Data_free" ? $R[$z] : 0);
                    } elseif (array_key_exists($z, $R)) json_row("$z-$C");
                }
            }
        }
        foreach ($Nh
                 as $z => $X) json_row("sum-$z", format_number($X));
        json_row("");
    } elseif ($_GET["script"] == "kill") $g->query("KILL " . number($_POST["kill"]));
    else {
        foreach (count_tables($b->databases()) as $m => $X) {
            json_row("tables-$m", $X);
            json_row("size-$m", db_size($m));
        }
        json_row("");
    }
    exit;
} else {
    $Wh = array_merge((array)$_POST["tables"], (array)$_POST["views"]);
    if ($Wh && !$o && !$_POST["search"]) {
        $H = true;
        $Le = "";
        if ($y == "sql" && $_POST["tables"] && count($_POST["tables"]) > 1 && ($_POST["drop"] || $_POST["truncate"] || $_POST["copy"])) queries("SET foreign_key_checks = 0");
        if ($_POST["truncate"]) {
            if ($_POST["tables"]) $H = truncate_tables($_POST["tables"]);
            $Le = 'Tables have been truncated.';
        } elseif ($_POST["move"]) {
            $H = move_tables((array)$_POST["tables"], (array)$_POST["views"], $_POST["target"]);
            $Le = 'Tables have been moved.';
        } elseif ($_POST["copy"]) {
            $H = copy_tables((array)$_POST["tables"], (array)$_POST["views"], $_POST["target"]);
            $Le = 'Tables have been copied.';
        } elseif ($_POST["drop"]) {
            if ($_POST["views"]) $H = drop_views($_POST["views"]);
            if ($H && $_POST["tables"]) $H = drop_tables($_POST["tables"]);
            $Le = 'Tables have been dropped.';
        } elseif ($y != "sql") {
            $H = ($y == "sqlite" ? queries("VACUUM") : apply_queries("VACUUM" . ($_POST["optimize"] ? "" : " ANALYZE"), $_POST["tables"]));
            $Le = 'Tables have been optimized.';
        } elseif (!$_POST["tables"]) $Le = 'No tables.';
        elseif ($H = queries(($_POST["optimize"] ? "OPTIMIZE" : ($_POST["check"] ? "CHECK" : ($_POST["repair"] ? "REPAIR" : "ANALYZE"))) . " TABLE " . implode(", ", array_map('idf_escape', $_POST["tables"])))) {
            while ($J = $H->fetch_assoc()) $Le .= "<b>" . h($J["Table"]) . "</b>: " . h($J["Msg_text"]) . "<br>";
        }
        queries_redirect(substr(ME, 0, -1), $Le, $H);
    }
    page_header(($_GET["ns"] == "" ? 'Database' . ": " . h(DB) : 'Schema' . ": " . h($_GET["ns"])), $o, true);
    if ($b->homepage()) {
        if ($_GET["ns"] !== "") {
            echo "<h3 id='tables-views'>" . 'Tables and views' . "</h3>\n";
            $Vh = tables_list();
            if (!$Vh) echo "<p class='message'>" . 'No tables.' . "\n"; else {
                echo "<form action='' method='post'>\n";
                if (support("table")) {
                    echo "<fieldset><legend>" . 'Search data in tables' . " <span id='selected2'></span></legend><div>", "<input type='search' name='query' value='" . h($_POST["query"]) . "'>", script("qsl('input').onkeydown = partialArg(bodyKeydown, 'search');", ""), " <input type='submit' name='search' value='" . 'Search' . "'>\n", "</div></fieldset>\n";
                    if ($_POST["search"] && $_POST["query"] != "") {
                        $_GET["where"][0]["op"] = "LIKE %%";
                        search_tables();
                    }
                }
                $cc = doc_link(array('sql' => 'show-table-status.html'));
                echo "<div class='scrollable'>\n", "<table cellspacing='0' class='nowrap checkable'>\n", script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"), '<thead><tr class="wrap">', '<td><input id="check-all" type="checkbox" class="jsonly">' . script("qs('#check-all').onclick = partial(formCheck, /^(tables|views)\[/);", ""), '<th>' . 'Table', '<td>' . 'Engine' . doc_link(array('sql' => 'storage-engines.html')), '<td>' . 'Collation' . doc_link(array('sql' => 'charset-charsets.html', 'mariadb' => 'supported-character-sets-and-collations/')), '<td>' . 'Data Length' . $cc, '<td>' . 'Index Length' . $cc, '<td>' . 'Data Free' . $cc, '<td>' . 'Auto Increment' . doc_link(array('sql' => 'example-auto-increment.html', 'mariadb' => 'auto_increment/')), '<td>' . 'Rows' . $cc, (support("comment") ? '<td>' . 'Comment' . $cc : ''), "</thead>\n";
                $S = 0;
                foreach ($Vh
                         as $C => $T) {
                    $aj = ($T !== null && !preg_match('~table~i', $T));
                    $u = h("Table-" . $C);
                    echo '<tr' . odd() . '><td>' . checkbox(($aj ? "views[]" : "tables[]"), $C, in_array($C, $Wh, true), "", "", "", $u), '<th>' . (support("table") || support("indexes") ? "<a href='" . h(ME) . "table=" . urlencode($C) . "' title='" . 'Show structure' . "' id='$u'>" . h($C) . '</a>' : h($C));
                    if ($aj) {
                        echo '<td colspan="6"><a href="' . h(ME) . "view=" . urlencode($C) . '" title="' . 'Alter view' . '">' . (preg_match('~materialized~i', $T) ? 'Materialized view' : 'View') . '</a>', '<td align="right"><a href="' . h(ME) . "select=" . urlencode($C) . '" title="' . 'Select data' . '">?</a>';
                    } else {
                        foreach (array("Engine" => array(), "Collation" => array(), "Data_length" => array("create", 'Alter table'), "Index_length" => array("indexes", 'Alter indexes'), "Data_free" => array("edit", 'New item'), "Auto_increment" => array("auto_increment=1&create", 'Alter table'), "Rows" => array("select", 'Select data'),) as $z => $A) {
                            $u = " id='$z-" . h($C) . "'";
                            echo($A ? "<td align='right'>" . (support("table") || $z == "Rows" || (support("indexes") && $z != "Data_length") ? "<a href='" . h(ME . "$A[0]=") . urlencode($C) . "'$u title='$A[1]'>?</a>" : "<span$u>?</span>") : "<td id='$z-" . h($C) . "'>");
                        }
                        $S++;
                    }
                    echo(support("comment") ? "<td id='Comment-" . h($C) . "'>" : "");
                }
                echo "<tr><td><th>" . sprintf('%d in total', count($Vh)), "<td>" . h($y == "sql" ? $g->result("SELECT @@storage_engine") : ""), "<td>" . h(db_collation(DB, collations()));
                foreach (array("Data_length", "Index_length", "Data_free") as $z) echo "<td align='right' id='sum-$z'>";
                echo "</table>\n", "</div>\n";
                if (!information_schema(DB)) {
                    echo "<div class='footer'><div>\n";
                    $Ui = "<input type='submit' value='" . 'Vacuum' . "'> " . on_help("'VACUUM'");
                    $wf = "<input type='submit' name='optimize' value='" . 'Optimize' . "'> " . on_help($y == "sql" ? "'OPTIMIZE TABLE'" : "'VACUUM OPTIMIZE'");
                    echo "<fieldset><legend>" . 'Selected' . " <span id='selected'></span></legend><div>" . ($y == "sqlite" ? $Ui : ($y == "pgsql" ? $Ui . $wf : ($y == "sql" ? "<input type='submit' value='" . 'Analyze' . "'> " . on_help("'ANALYZE TABLE'") . $wf . "<input type='submit' name='check' value='" . 'Check' . "'> " . on_help("'CHECK TABLE'") . "<input type='submit' name='repair' value='" . 'Repair' . "'> " . on_help("'REPAIR TABLE'") : ""))) . "<input type='submit' name='truncate' value='" . 'Truncate' . "'> " . on_help($y == "sqlite" ? "'DELETE'" : "'TRUNCATE" . ($y == "pgsql" ? "'" : " TABLE'")) . confirm() . "<input type='submit' name='drop' value='" . 'Drop' . "'>" . on_help("'DROP TABLE'") . confirm() . "\n";
                    $l = (support("scheme") ? $b->schemas() : $b->databases());
                    if (count($l) != 1 && $y != "sqlite") {
                        $m = (isset($_POST["target"]) ? $_POST["target"] : (support("scheme") ? $_GET["ns"] : DB));
                        echo "<p>" . 'Move to other database' . ": ", ($l ? html_select("target", $l, $m) : '<input name="target" value="' . h($m) . '" autocapitalize="off">'), " <input type='submit' name='move' value='" . 'Move' . "'>", (support("copy") ? " <input type='submit' name='copy' value='" . 'Copy' . "'> " . checkbox("overwrite", 1, $_POST["overwrite"], 'overwrite') : ""), "\n";
                    }
                    echo "<input type='hidden' name='all' value=''>";
                    echo
                    script("qsl('input').onclick = function () { selectCount('selected', formChecked(this, /^(tables|views)\[/));" . (support("table") ? " selectCount('selected2', formChecked(this, /^tables\[/) || $S);" : "") . " }"), "<input type='hidden' name='token' value='$pi'>\n", "</div></fieldset>\n", "</div></div>\n";
                }
                echo "</form>\n", script("tableCheck();");
            }
            echo '<p class="links"><a href="' . h(ME) . 'create=">' . 'Create table' . "</a>\n", (support("view") ? '<a href="' . h(ME) . 'view=">' . 'Create view' . "</a>\n" : "");
            if (support("routine")) {
                echo "<h3 id='routines'>" . 'Routines' . "</h3>\n";
                $Wg = routines();
                if ($Wg) {
                    echo "<table cellspacing='0'>\n", '<thead><tr><th>' . 'Name' . '<td>' . 'Type' . '<td>' . 'Return type' . "<td></thead>\n";
                    odd('');
                    foreach ($Wg
                             as $J) {
                        $C = ($J["SPECIFIC_NAME"] == $J["ROUTINE_NAME"] ? "" : "&name=" . urlencode($J["ROUTINE_NAME"]));
                        echo '<tr' . odd() . '>', '<th><a href="' . h(ME . ($J["ROUTINE_TYPE"] != "PROCEDURE" ? 'callf=' : 'call=') . urlencode($J["SPECIFIC_NAME"]) . $C) . '">' . h($J["ROUTINE_NAME"]) . '</a>', '<td>' . h($J["ROUTINE_TYPE"]), '<td>' . h($J["DTD_IDENTIFIER"]), '<td><a href="' . h(ME . ($J["ROUTINE_TYPE"] != "PROCEDURE" ? 'function=' : 'procedure=') . urlencode($J["SPECIFIC_NAME"]) . $C) . '">' . 'Alter' . "</a>";
                    }
                    echo "</table>\n";
                }
                echo '<p class="links">' . (support("procedure") ? '<a href="' . h(ME) . 'procedure=">' . 'Create procedure' . '</a>' : '') . '<a href="' . h(ME) . 'function=">' . 'Create function' . "</a>\n";
            }
            if (support("sequence")) {
                echo "<h3 id='sequences'>" . 'Sequences' . "</h3>\n";
                $kh = get_vals("SELECT sequence_name FROM information_schema.sequences WHERE sequence_schema = current_schema() ORDER BY sequence_name");
                if ($kh) {
                    echo "<table cellspacing='0'>\n", "<thead><tr><th>" . 'Name' . "</thead>\n";
                    odd('');
                    foreach ($kh
                             as $X) echo "<tr" . odd() . "><th><a href='" . h(ME) . "sequence=" . urlencode($X) . "'>" . h($X) . "</a>\n";
                    echo "</table>\n";
                }
                echo "<p class='links'><a href='" . h(ME) . "sequence='>" . 'Create sequence' . "</a>\n";
            }
            if (support("type")) {
                echo "<h3 id='user-types'>" . 'User types' . "</h3>\n";
                $Si = types();
                if ($Si) {
                    echo "<table cellspacing='0'>\n", "<thead><tr><th>" . 'Name' . "</thead>\n";
                    odd('');
                    foreach ($Si
                             as $X) echo "<tr" . odd() . "><th><a href='" . h(ME) . "type=" . urlencode($X) . "'>" . h($X) . "</a>\n";
                    echo "</table>\n";
                }
                echo "<p class='links'><a href='" . h(ME) . "type='>" . 'Create type' . "</a>\n";
            }
            if (support("event")) {
                echo "<h3 id='events'>" . 'Events' . "</h3>\n";
                $K = get_rows("SHOW EVENTS");
                if ($K) {
                    echo "<table cellspacing='0'>\n", "<thead><tr><th>" . 'Name' . "<td>" . 'Schedule' . "<td>" . 'Start' . "<td>" . 'End' . "<td></thead>\n";
                    foreach ($K
                             as $J) {
                        echo "<tr>", "<th>" . h($J["Name"]), "<td>" . ($J["Execute at"] ? 'At given time' . "<td>" . $J["Execute at"] : 'Every' . " " . $J["Interval value"] . " " . $J["Interval field"] . "<td>$J[Starts]"), "<td>$J[Ends]", '<td><a href="' . h(ME) . 'event=' . urlencode($J["Name"]) . '">' . 'Alter' . '</a>';
                    }
                    echo "</table>\n";
                    $_c = $g->result("SELECT @@event_scheduler");
                    if ($_c && $_c != "ON") echo "<p class='error'><code class='jush-sqlset'>event_scheduler</code>: " . h($_c) . "\n";
                }
                echo '<p class="links"><a href="' . h(ME) . 'event=">' . 'Create event' . "</a>\n";
            }
            if ($Vh) echo
            script("ajaxSetHtml('" . js_escape(ME) . "script=db');");
        }
    }
}
page_footer();