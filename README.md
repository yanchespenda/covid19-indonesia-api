## API Covid 19 Indonesia
### Route
- /kasus
- /nasional/positif
- /nasional/sembuh
- /nasional/meninggal
- /provinsi
- /provinsi/positif
- /provinsi/sembuh
- /provinsi/meninggal
- /provinsi/odp
- /provinsi/pdp

### Bentuk Respon API
```
{
    'status': true|false,
    'message': 'Success'|'Failed',
    'data': 'Array data'
}
```

Contoh data
```
{
    '#Wilayah#': {
        'positif|sembuh|meninggal|odp|pdp': 0,
        'center': {
            'lat': 'Kordinat Lat (Bentuk double)',
            'lng': 'Kordinat Lng (Bentuk double)'
        },
        'radius': 'Nilai radius untuk besaran bulatan di google maps nya (Bentuk integer)'
    }
}
```

## License

The project licensed under the [MIT license](https://opensource.org/licenses/MIT).
