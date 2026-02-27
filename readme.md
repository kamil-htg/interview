We have to build offer exporter for two of our partners: A and X.
Both of them expect XML but with different structure.
Some information in the export are based on the input data (provided in query parameters).

---

Sample XML for A:
```xml
<?xml version="1.0" encoding="utf-8"?>
<bundle>
    <accomodation>
        <id>123</id>
        <place_name>Offer name</place_name>
        <place_description><![CDATA[Lovely place to spend time with family and friends.]]></place_description>
        <label>Optional Label</label>
        <location>
            <latitude>52.231949607764314</latitude>
            <longitude>21.005984261088575</longitude>
        </location>
        <images>
            <image><![CDATA[https://picsum.photos/600/400]]></image>
            <image><![CDATA[https://picsum.photos/600/400]]></image>
        </images>
    </accomodation>
</bundle>
```

Parameters A can provide via query:
- `id` (required) offer id
- `name` (optional) override offer name
- `label` (optional) offer label, if not provided, the `label` field should be removed from the XML

---

Sample XML for X:
```xml
<?xml version="1.0" encoding="utf-8"?>
<offers>
    <offer id="123">
        <name></name>
        <provider>HTG</provider>
        <providerOfferId>123</providerOfferId>
        <pax>3</pax>
        <dateStart>2026-03-17</dateStart>
        <dateEnd>2026-03-19</dateEnd>
    </offer>
</offers>
```
Parameters X can provide via query:
- `id` (required) offer id
- `name` (optional) override offer name
- `pax` (required) number of people (incl. children)
- `dateStart` (required) start date of the offer, format: YYYY-MM-DD
- `dateEnd` (required) end date of the offer, format: YYYY-MM-DD


---

Oh, btw we are in discussion with another partner (Y) and they also mentioned offer export but didn't provide any details yet.
