<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:template match="/podaci">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>Hrvatske Crkve</title>
                <meta charset="UTF-8" />
                <link rel="stylesheet" type="text/css" href="./dizajn.css" />
                <link rel="stylesheet" type="text/css" href="./dizajn/pretvorba.css"/>
            </head>
            <body>
                <header>
      <figure>
        <a href="./index.html" class="naslovna">
          <img src="./slike/cross.png" alt="Naslovna slika." />
        </a>
      </figure>
      <h4>Crkve Hrvatske</h4>
    </header>

    <nav>
      <ul>
        <li>
          <a href="./index.html">Početna</a>
        </li>
        <li>
          <a href="./obrazac.html">Pretraživanje</a>
        </li>
        <li>
          <a href="./podaci.xml">Popis Crkvi</a>
        </li>
        <li>
          <a href="http://www.fer.unizg.hr/predmet/or">Otvoreno Računarstvo</a>
        </li>
        <li>
          <a href="http://www.fer.unizg.hr/" target="_blank">FER</a>
        </li>
        <li>
          <a href="mailto:antonio.kamber@fer.hr">Email</a>
        </li>
      </ul>
    </nav>
                <main>
                    <section>
                      <xsl:attribute name="id">pretvorba-tablica</xsl:attribute>
                        <table>
                    <thead>
                        <th>Naziv</th>
                        <th>Raspored Misa</th>
                        <th>Župnik</th>
                        <th>Mjesto</th>
                        <th>Kapacitet</th>
                        <th>Aktivnosti</th>
                    </thead>
                    <tbody>
                        <xsl:for-each select="crkva">
                          <xsl:sort select="god_osnutka" />
                            <tr>
                                <td><xsl:value-of select="naziv"/></td>
                                <td>
                                  <xsl:for-each select="misa">
                                    <xsl:value-of select="@dan_u_tjednu"/>, <xsl:value-of select="vrijeme"/>h<br/>
                                    <xsl:if test="position()!=last()">
                                      <hr/>
                                    </xsl:if>
                                  </xsl:for-each>
                                </td>
                                <td>
                                    <xsl:value-of select="župnik/ime"/>&#160;<xsl:value-of select="župnik/prezime"/><br/>
                                    Kontakt:
                                    <xsl:for-each select="župnik/telefon">
                                      <xsl:value-of select="@br_mreze"/><xsl:value-of select="text()"/>
                                      <br/>
                                    </xsl:for-each>
                                </td>
                                <td> <xsl:value-of select="adresa/mjesto/@pos_broj"/>, <xsl:value-of select="adresa/mjesto"/></td>
                                <td>
                                  <xsl:if test="max_kapacitet &gt; 100">
                                    Maksimalno <xsl:value-of select="max_kapacitet"/>
                                  </xsl:if>
                                  <xsl:if test="max_kapacitet &lt; 100">
                                    Do 100
                                  </xsl:if>
                                  <xsl:if test="not(max_kapacitet)">Nepoznato</xsl:if>
                                </td>
                                <td>
                                  <!-- može i sa if ali sam koristio choose radi edukacije -->
                                  <xsl:choose>
                                    <xsl:when test="count(aktivnost) > 0">
                                      <xsl:for-each select="aktivnost">
                                            <xsl:value-of select="@naziv"/>
                                            <br/>
                                      </xsl:for-each>
                                    </xsl:when>
                                    <xsl:otherwise>Ne postoje aktivnosti</xsl:otherwise>
                                  </xsl:choose>
                                </td>
                            </tr>
                        </xsl:for-each>
                    </tbody>
                </table>
                    </section>
                </main>
                <footer>
      Autor: Antonio Kamber
    </footer>
            </body>
        </html>
    </xsl:template>
    <xsl:output method="xml" indent="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" />
</xsl:stylesheet>