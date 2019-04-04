<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:template match="/podaci">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>Hrvatske Crkve</title>
                <meta charset="UTF-8" />
                <link rel="stylesheet" type="text/css" href="../dizajn.css" />
            </head>
            <body>
                <header>
      <figure>
        <a href="./index.html" class="naslovna">
          <img src="../slike/cross.png" alt="Naslovna slika." />
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
          <a href="./data/podaci.xml">Popis Crkvi</a>
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
                        <table>
                    <thead>
                        <th>Naziv</th>
                        <th>Mjesto</th>
                        <th>Kategorija</th>
                        <th>Župnik</th>
                        <th>Župni ured</th>
                        <th>Godina osnutka</th>
                        <th>Kapacitet</th>
                    </thead>
                    <tbody>
                        <xsl:for-each select="crkva">
                            <tr>
                                <td><xsl:value-of select="naziv"/></td>
                                <td> <xsl:value-of select="adresa/mjesto/@pos_broj"/> - <xsl:value-of select="adresa/mjesto"/></td>
                                <td><xsl:value-of select="@kategorija"/></td>
                                <td><xsl:value-of select="župnik/ime"/> <xsl:value-of select="župnik/prezime"/>
                                </td>
                                <td>
                                    <xsl:if test="župni_ured/mail != ''">
                                        <xsl:value-of select="župni_ured/mail"/>
                                    </xsl:if>
                                    <xsl:if test="not(župni_ured/mail)">
                                        -
                                    </xsl:if>
                                </td>
                                <td><xsl:if test="god_osnutka != ''">
                                    <xsl:value-of select="god_osnutka"/>
                                </xsl:if>
                                <xsl:if test="not(god_osnutka)">
                                    Nepoznato
                                </xsl:if>
                                </td>
                                <td><xsl:if test="max_kapacitet != ''">
                                    <xsl:value-of select="max_kapacitet"/>
                                </xsl:if>
                                <xsl:if test="not(max_kapacitet)">
                                    Nepoznato
                                </xsl:if>
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