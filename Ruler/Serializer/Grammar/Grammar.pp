%skip  space    \s
// Scalars.
%token  true           true
%token  false          false
%token  null           null
%token  number         \d+
// Strings.
%token  quote_         "        -> string
%token  string:string  [^"]+
%token  string:_quote  "        -> default
%token  bracket_       \(
%token _bracket        \)
%token key             [a-zA-Z.]+
%token value           [0-9]

// operators
%token operator        [!=<>]+

// logical operators
%token and             \&\&
%token or              \|\|

#expression:
    rule()
    ( ( ::and:: #and | ::or:: #or) expression() )?

rule:
    ( ::bracket_:: expression() ::_bracket:: ) |
    <key> operator() value()

operator:
    <operator>

value:
    <true> | <false> | <null> | string() | number()

string:
    ::quote_:: <string> ::_quote::

number:
    <number>
