# 12 band Parametric EQ (Eqfa12p) for rAudio
>
> wget -O - https://raw.githubusercontent.com/lovehifi/eqfa12/main/install.sh | sh
>
>
Parametric Eqfa12 Settings page:
>
http://raudio/eqfa12 or http://ip/eqfa12
>

#### Use for MPD rAuido
Edit file /srv/http/data/mpdconf/mpd.conf
>
Add comment # this line, like this: 
>
> #include_optional    "output.conf"
>
And add this line:
>
> include_optional    "eq12.conf"
>
>
####  Use for SQ LMSrAudio
>
Change the output to **-o eqfa12p**, like this:
>
> /opt/sq/squeezelite64 -o eqfa12p -n SQ64-rAudio -s 127.0.0.1 -m 00:00:00:00:00:00 -W
>
![Screenshot](eqfa12.png)

Build from Repo: 
> https://github.com/bitkeeper/capsc
>
EQ information
> https://github.com/jaakkopasanen/AutoEq
>
> https://www.bitlab.nl/page_id=540
