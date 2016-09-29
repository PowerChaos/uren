# uren
uur registratie systeem met projecten
custumor roadmap:
inloggen, persoonlijke maandelijks overzicht, 
overzicht reeds ingegeven dagen/niet ingegeven dagen/niet
dag drukken = ofwel reeds ingegeven= info detailsofwel nog niet ingegeven aw, ingave aw op die dag, uur selectie van tot en save

dan kleur blauw bij dagen ingegeven aanwezigheids
overzicht uren AW

op dag drukken - ingave detail, (formulier) opsplitsing uren, toevoegen klant, project, fase, omschrijving, optie TE FACTUREREN?, MATERIAAL GEBRUIKT?, ACTION REQUIRED
bij ingave, live vieuw, uren aw (8), uren detail (0), uren te factureren (0)

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
| id |   naam 	 | wachtwoord | rechten |
|----|-----------|------------|---------|
| 1	 |	 Admin	 | 	  SHA1	  |    3  	|
| 15 | 	 User	 |    SHA1	  |	   1  	|
### info
1 = gebruiker
2 = staff
3 = admin

## klanten
| id |	 naam  | email | adress | telefoon | klantcode
|----|---------|-------|--------|----------|
| 2	 | Klant A |  t@t  | adress |  123456  |

## Projecten
| id | klant |    naam 	 | 1ste tabel=projDBN   / Code
|----|-------|-----------|
| 3	 |   2   | Project A |

## Aanwezig
| AW_id |  uid | van  |  tot  |	  Datum   | AWTOTAALuren | info | awdetail /  Cruid
|---   -|------|------|---- --|-----------|------         |------|
| 4	    |	15  | 08:30| 16:30|01/01/2016 |   8	         | info |  1=volledig

## details
| det_id | awid |  van  |  tot  |	 datum	 | kid | pid | uren | factuur | info | /Materiaal nodig?/ MatReqNR / Action required?/ Type Action?/ cruid  / KCode  / PCode  / evtFase?  / TEFACTUREREN?  / GEFACTUREERD?/ FN?
|--    --|------|-------|-------|------------|-----|-----|------|---------|------|
| 5	     |	 4  | 08:30 | 10:30 | 01/01/2016 | 15  |  3  | 	2   |	y     | info |
| 6	     |	 4  | 11:30 | 16:30 | 01/01/2016 | 15  |  3  | 	5   |	n     | info |
| 7	     |	 4  | 10:30 | 11:30 | 01/01/2016 | 15  |  3  | 	1   |	b     | info |

### info
b = betaald
y = factureren
n = niet factureren


##### Materiaal request tabel

###Materiaal gebruik

