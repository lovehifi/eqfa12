# 12 band Parametric EQ (Eqfa12p) for rAudio
>
> wget -O - https://raw.githubusercontent.com/lovehifi/eqfa12/main/install.sh | sh
>

#### MPD rAuido
Edit file /srv/http/data/mpdconf/mpd.conf
Add comment # this line, like this: 
> #include_optional    "output.conf"
And add this line:
> include_optional    "eq12.conf"
>
>
#### LMS-SQ
>
Output **-o eqfa12p**
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
