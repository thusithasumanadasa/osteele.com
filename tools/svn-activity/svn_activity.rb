require 'spark'
require 'time'

def make_activity_graph location='http://svn.openlaszlo.org/openlaszlo/trunk', options={}
  ext = options[:format] || 'png'
  require 'fileutils'
  FileUtils::mkdir_p 'cache'
  base = File.join('cache', CGI.escape("#{location}.#{ext}"))
  return base if File.exists?(base) and File.mtime(base)+10*60 > Time.now
  
  days = 30
  now = Time.now
  firstTime = Time.at(Time.gm(now.year, now.month, now.day).to_i-days*24*60*60).strftime('%Y-%m-%d')
  log = `svn log --xml -r {#{firstTime}}:HEAD '#{location.gsub(/['\\]/, '\\\\\0')}'`
  revision = log.scan(%r|<logentry\s+revision="(.*?)"|m).last[0]
  
  fname = File.join('cache', CGI.escape("#{location}-#{revision}.#{ext}"))
  return fname if File.exists?(fname)
  
  hist = [0]*days
  log.scan(%r|<date>(.*?)T|).each do |ts|
    ago = now.yday - Time.parse(ts.first).yday
    next if ago >= days
    hist[-ago-1] += 1
  end
  hist = hist.map{|n|n*100.0/hist.max} if hist.max > 0
  
  png = fname.sub(/[^.]*$/, 'png')
  Spark.plot_to_file(png, hist, :type => 'area', :step => 4, :upper => 0, :above_color => 'green', :height => 15)
  `convert #{png} #{fname}` unless fname == png
  FileUtils::ln fname, base, :force => true
  return fname
end
