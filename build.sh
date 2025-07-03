#!/bin/bash

BUILD_DIR="build"
SRC_DIR="src"
PUBLIC_DIR="public"

mkdir -p "$BUILD_DIR"
rm -rf "$BUILD_DIR"/*

#NOME DO BUILD
build_name() {
  echo "$1" | sed -E 's|^public/||; s|/|_|g; s|\.php$||'
}

#REGEX DE LIMPEZA DO CÓDIGO
clean_code() {
  sed -E '
    /^\s*<\?php\s*$/d;
    /^\s*\?>\s*$/d;
    /^\s*namespace\s+[A-Za-z0-9_\\]+;\s*$/d;
    /^\s*use\s+[A-Za-z0-9_\\]+(\s+as\s+[A-Za-z0-9_]+)?;\s*$/d;
    /^\s*require(_once)?\s+/d;
  ' "$1"
}

#PERCORRE OS ARQUIVOS PUBLICOS
find "$PUBLIC_DIR" -type f -name "*.php" | while read pubfile; do
  name=$(build_name "$pubfile")
  output_file="$BUILD_DIR/${name}.php"

  echo "<?php" > "$output_file"
  echo "" >> "$output_file"

  #PEGA AS CLASSES DO SRC
  find "$SRC_DIR" -type f -name "*.php" | sort | while read srcfile; do
    echo "// ==== BEGIN: $srcfile ====" >> "$output_file"
    clean_code "$srcfile" >> "$output_file"
    echo "// ==== END: $srcfile ====" >> "$output_file"
    echo "" >> "$output_file"
  done

  #HANDLER DA PUBLIC
  echo "// ==== BEGIN: $pubfile ====" >> "$output_file"
  clean_code "$pubfile" >> "$output_file"
  echo "// ==== END: $pubfile ====" >> "$output_file"
  echo "" >> "$output_file"

  echo "✅ Build criado: $output_file"
done
