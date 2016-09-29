# uren
uur registratie systeem met projecten

## RoadMap
+ Registreer projecten
+ Registreer klanten
+ rechten
+ overzicht
+ Facturatie zoekmode
+ totaal uren
+ uren te factureren
+ aanwezigheids uren
+ totaal uren per week

execute de SQL script en je kan beginnen 

gebruiker : admin 

wachtwoord : 123456

#Database opbouw

## gebruikers
| id |   naam 	 | wachtwoord | rechten |groep|
|----|-----------|------------|---------|-----|
| 1	 |	 Admin	 | 	  SHA1	  |    3  	|  1  |
| 15 | 	 User	 |    SHA1	  |	   1  	|     |
### info
- 1 = gebruiker
- 2 = staff
- 3 = admin

## groep
| id | user  |    naam 	 |
|----|-------|-----------|
| 1  |  1,15 | TestGroep |

## klanten
| id |	 naam  | email | adress | telefoon |
|----|---------|-------|--------|----------|
| 2	 | Klant A |  t@t  | adress |  123456  |

## Projecten
| id | klant |    naam 	 |
|----|-------|-----------|
| 3	 |   2   | Project A |

## Aanwezig
| id |  uid | van  |  tot |	  Datum   | uren |gefactureerd|
|----|------|------|------|-----------|------|------------|
| 4	 |	15  | 08:30| 16:30|01/01/2016 |   8	 |		n	  |

### Factuur
+ y = bevestigd
+ n = niet bevestigd

## details
| id | awid |  van  |  tot  |	 datum	 | kid | pid | uren | factuur | info |
|----|------|-------|-------|------------|-----|-----|------|---------|------|
| 5	 |	 4  | 08:30 | 10:30 | 01/01/2016 | 15  |  3  | 	2   |	y     | info |
| 6	 |	 4  | 11:30 | 16:30 | 01/01/2016 | 15  |  3  | 	5   |	n     | info |
| 7	 |	 4  | 10:30 | 11:30 | 01/01/2016 | 15  |  3  | 	1   |	y     | info |

### info
+ y = factureren
+ n = niet factureren
