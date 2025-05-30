# ATL (Advanced Tape Language)
Kendini ifade gücü yüksek ezoterik turing complete programlama dili.

## Şuanki Komut Seti

| sembol | mnemonic | açıklama |
| -------|----------|--------- |
|`:!=?N` | neq_go   | hücredeki sayı sıfıra eşit değilse N git |
|`:=?N`  | eq_go    | hücredeki sayı sıfıra eşitse N git |
|`:>?N`  | gtz_go   | hücredeki sayı sıfırdan küçükse N git |
|`:<?N`  | ltz_go   | hücredeki sayı sıfırdan büyükse N git |
|`:pN`   | push num | hücreye sayı (N) yolla |
|`:p"S"`   | push str | hücreye string (S) yolla|
|`:<` | lgo | sola git |
|`:>` | rgo | sağa git |
|`:w` | write | hücreyi ekrana yaz |
|`:r` | read | girdi al hücreye yaz |
|`:q` | quit | programı kapat |
|`:c` | clear | ekranı temizle |
|`:x` | reset | bütün hücreleri |
|`:d` | reset | debug mod |

## Veri Tipleri

| biçim | tür |
| ------|---- |
| `123` | Numeric |
| `"Hello"` | String |

## ATL'nin Özelliği

- Minimal komut seti kullanımı kolaylaştırıyor.
- Sadelik konusunda APL'den örnek aldım.
- Kullanıcıya gereksiz soyutlamalar vermiyor.
- Regex kullanımını öğrenmek için ideal.

## Kod Örnekleri
- Ekrana 5 kere "Selam" yazdır.
  - `:p"Selam":>:p5:<:w:>:--:!=?-4`
- 0'dan girilen N sayısına kadar yazdır.
  - `:r:>:++:w:<:--:!=?-5`

